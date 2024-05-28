<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Zonas;
use App\Models\MetricasHistory;
use App\Models\PagosFechas;
use App\Models\GastosOperaciones;
use App\Models\Credito;
use App\Models\Pagos;

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
        $dt_end = date('Y-m-d');

        $array_to_insert    = [];

        $D1     = date('Y-m-01', strtotime($dt_end)). ' 00:00:00';
        $D2     = $dt_end . ' 23:59:59';

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

            if ( $ttCuotaCobrada > 0) {
                $array_to_insert[$key] = [
                    "INGRESO"           => $ttCuotaCobrada,
                    "CAPITAL"           => $ttPagoCapital,
                    "INTERESES"         => $ttPagoIntereses,
                    "UTIL_NETA"         => $ttUtilidadNeta,
                    "SALDOS_CARTERA"    => $Saldos_Cartera,
                    "MORA_ATRASADA"     => $MoraAtrasada,
                    "MORA_VENCIDA"      => $MoraVencida,
                    "clientes_activos"  => $Clientes->count(),
                    'Zona_id'           => $Id_Zona,
                    'rol_id'            => $Id_Rol,
                    'Fecha'             => $dt_end,
                ];
            }
        }
        
        MetricasHistory::insert($array_to_insert);


    }
}

