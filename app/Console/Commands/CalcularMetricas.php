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
use App\Models\Abono;
use Illuminate\Support\Facades\DB;

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
        $D2     = date('Y-m-d', strtotime($dtNow)). ' 23:59:59'; 


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
            $Dispensa               = Abono::Dispensa($D1, $D2);

            

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
        
                    $array_consolidado = [
                        [
                            "Fecha"     => $dtNow,
                            'num_month' => date('m', strtotime($dtNow)),
                            'num_year'  => date('Y', strtotime($dtNow)),
                            "Concepto"  => "gastos_operativos",
                            "Valor"     => $GastosOperativos
                        ],
                        [
                            "Fecha"     => $dtNow,
                            'num_month' => date('m', strtotime($dtNow)),
                            'num_year'  => date('Y', strtotime($dtNow)),
                            "Concepto"  => "mora_atrasada",
                            "Valor"     => $MoraAtrasada
                        
                        ],
                        [
                            "Fecha"     => $dtNow,
                            'num_month' => date('m', strtotime($dtNow)),
                            'num_year'  => date('Y', strtotime($dtNow)),
                            "Concepto"  => "mora_vencida",
                            "Valor"     => $MoraVencida
                        ],             
                        [
                            "Fecha"     => $dtNow,
                            'num_month' => date('m', strtotime($dtNow)),
                            'num_year'  => date('Y', strtotime($dtNow)),
                            "Concepto"  => "saldo_cartera",
                            "Valor"     => $Saldos_Cartera
                        ],
                        [
                            "Fecha"     => $dtNow,
                            'num_month' => date('m', strtotime($dtNow)),
                            'num_year'  => date('Y', strtotime($dtNow)),
                            "Concepto"  => "clientes_nuevos",
                            "Valor"     => $ClientesNuevos
                        ],
                        [
                            "Fecha"     => $dtNow,
                            'num_month' => date('m', strtotime($dtNow)),
                            'num_year'  => date('Y', strtotime($dtNow)),
                            "Concepto"  => "clientes_activos",
                            "Valor"     => $Clientes->count()
                        ],
                        [
                            "Fecha"     => $dtNow,
                            'num_month' => date('m', strtotime($dtNow)),
                            'num_year'  => date('Y', strtotime($dtNow)),
                            "Concepto"  => "clientes_renovados",
                            "Valor"     => $ClientesRenovados
                        ],
                        [
                            "Fecha"     => $dtNow,
                            'num_month' => date('m', strtotime($dtNow)),
                            'num_year'  => date('Y', strtotime($dtNow)),
                            "Concepto"  => "ingreso_neto",
                            "Valor"     => $ttCuotaCobrada
                        ],
                        [
                            "Fecha"     => $dtNow,
                            'num_month' => date('m', strtotime($dtNow)),
                            'num_year'  => date('Y', strtotime($dtNow)),
                            "Concepto"  => "utilidad_bruta",
                            "Valor"     => $ttPagoIntereses
                        ],
                        [
                            "Fecha"     => $dtNow,
                            'num_month' => date('m', strtotime($dtNow)),
                            'num_year'  => date('Y', strtotime($dtNow)),
                            "Concepto"  => "dispensa_aplicada",
                            "Valor"     => $Dispensa
                        ]
                    ];
                    

                }
                


            }
        }

        //INSERTA METRICAS ACTUALIES 

        //Borra las metricas de MetricasHistory para el Fecha que contiene $dtNow
        MetricasHistory::where('Fecha', $dtNow)->delete();
        MetricasHistory::insert($array_to_insert);        
        
        // ELIMINA REGISTROS ALMACENADOS DE FECHA Y GUARDADO DE INFORMACION
        Consolidado::where('num_month', date('m'))
                ->where('num_year', date('Y'))
                ->whereNotIn('Concepto', ['util_reinvertidas', 'util_provicion', 'desembolso_mes', 'reinvercion_capital', 'efectivo_disp'])
                ->delete();
        Consolidado::insert($array_consolidado);

        DB::statement('CALL actualizar_tabla_abonos()');




    }
}

