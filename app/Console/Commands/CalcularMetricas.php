<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Zonas;
use App\Models\MetricasHistory;
use App\Models\PagosFechas;
use App\Models\GastosOperaciones;
use App\Models\Credito;
use App\Models\Pagos;
use App\Models\Consolidado;

class CalcularMetricas extends Command
{
    // El nombre y la firma del comando de la consola.
    protected $signature = 'metricas:calcular';

    // La descripción del comando de la consola.
    protected $description = 'Calcular métricas diariamente';

   // Ejecuta el comando de la consola.
    public function handle()
    {
        $array_zonas = Zonas::where('activo', 1)->pluck('id_zona')->toArray();
        $array_zonas[-1] = -1;        
        
        $Id_Rol = 1;

        $array_to_insert      = [];
        $array_consolidado    = [];

        $dtNow  = date('Y-m-d');
        $D1     = date('Y-m-01', strtotime($dtNow)). ' 00:00:00';
        $D2     = date('Y-m-t', strtotime($dtNow)). ' 23:59:59'; 

        foreach ($array_zonas as $key => $z) {
                        
            $Id_Zona            = $z;
            $ttPagoCapital      = 0;
            $ttPagoIntereses    = 0;

            $MoraAtrasada = PagosFechas::getMoraCalcHistory($Id_Zona,'atrasada',$D1, $D2);
            $MoraVencida  = PagosFechas::getMoraCalcHistory($Id_Zona,'vencida',$D1, $D2);
            
            $Dias = Pagos::selectRaw('SUM((CASE WHEN FECHA_ABONO <= "2024-03-16" THEN CAPITAL ELSE CAPITAL END)) CAPITAL, SUM(INTERES) INTERES')
                    ->whereBetween('FECHA_ABONO', [$D1, $D2])
                    ->where('activo', 1)
                    ->when($Id_Zona > -1, function ($query) use ($Id_Zona) {
                        $query->where('id_zona', $Id_Zona);
                    })
                    ->groupByRaw('DAY(FECHA_ABONO)')
                    ->get();

            
            foreach ($Dias as $dia) {
                $ttPagoCapital      += $dia->CAPITAL; 
                $ttPagoIntereses    += $dia->INTERES; 
            }
            $Saldos_Cartera = Credito::Saldos_Cartera($Id_Zona,$D1, $D2);
            $Clientes       = Credito::Creditos($Id_Zona,$D1, $D2);            
        

            $GastosOperativos = GastosOperaciones::whereBetween('fecha_gasto', [$D1, $D2])->where('activo', 1)->sum('monto');

            $ttCuotaCobrada     = $ttPagoCapital + $ttPagoIntereses;

            $ttUtilidadNeta = $ttPagoIntereses - $GastosOperativos ;

            //Clientes Nuevos y Renovados
            $ClientesRenovados      = Credito::ClientesRenovados($Id_Zona,$D1, $D2);
            $ClientesNuevos         = Credito::ClientesNuevos($Id_Zona,$D1, $D2);

            if ( $ttCuotaCobrada > 0) {
                $array_to_insert[$key] = [
                    "INGRESO"           => $ttCuotaCobrada,
                    "CAPITAL"           => $ttPagoCapital,
                    "INTERESES"         => $ttPagoIntereses,
                    "UTIL_NETA"         => $ttUtilidadNeta,
                    "SALDOS_CARTERA"    => $Saldos_Cartera,
                    "MORA_ATRASADA"     => $MoraAtrasada,
                    "MORA_VENCIDA"      => $MoraVencida,                    
                    'Zona_id'           => $Id_Zona,
                    'rol_id'            => $Id_Rol,
                    'Fecha'             => $dtNow,
                    "clientes_activos"      => $Clientes->count(),
                ];

                if ($Id_Zona < 0) {
                    $array_consolidado[$key] = [
                        'fecha'             => $dtNow,
                        "clientes_activos"      => $Clientes->count(),
                        "clientes_renovados"    => $ClientesRenovados,
                        "clientes_nuevos"       => $ClientesNuevos,
                        "gastos_operativos"     => $GastosOperativos,
                        "mora_atrasada"         => $MoraAtrasada,
                        "mora_vencida"          => $MoraVencida, 
                        "saldo_cartera"         => $Saldos_Cartera,
                    ];
                }
                


            }
        }
        //MetricasHistory::insert($array_to_insert);
        //Guarda los registros a la tabla consolidado        
        Consolidado::insert($array_consolidado);

    }
}

