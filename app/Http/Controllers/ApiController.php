<?php
namespace App\Http\Controllers;
use GMP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Credito;
use Carbon\Carbon;
use Mavinoo\Batch\Batch;

class ApiController extends Controller{
    public function CalcularEstados()
    {  

        // php artisan run:CalcularEstadosCredito
        $Creditos_mora    = [];
        $Creditos_venc    = [];
        $Creditos_merg    = [];
        $idEstado         = 1; 
        $DsVenc           = 0;
        $batch_Credito    = new Credito;
        $batch_index      = 'id_creditos';
        //ENTIENDA ACTIVO COMO QUE EL CREDITO NO SE A REMOVIDO
        $Creditos_Activos =  Credito::getCreditosActivos();        

        foreach ($Creditos_Activos as $key => $c) {
            //VERIFICA SI EL ULTIMO ABONO APLCIADO EXCEDE A LA FECHA ACTUAL           
            $last_abono = ($c->abonos->isNotEmpty()) ? $c->abonos->first()->fecha_cuota : $c->fecha_apertura ;
            $date_ref_abono = $c->refAbonos->first()->FechaPago;

            //VERIFICA QUE SI EL ULTIMO PAGO ES MAYOR DE 7 DIAS PARA PONERLO EN MORA
            $fechaDada      = Carbon::parse($last_abono);
            $fechaActual    = Carbon::now();
            $diferenciaDias = $fechaDada->diffInDays($fechaActual);

            //CALCULA SI LA CUENTA SE ENCUENTRA VENCINA
            $dtEnds         = Carbon::parse($date_ref_abono);
            $dtNow          = Carbon::now();
            $DsVenc         = $dtNow->diffInDays($dtEnds,false);

            if ($DsVenc < 0 ) {
                $Creditos_venc[$key] = [
                    'id_creditos'       => $c->id_creditos,
                    //'estado_credito'    => ($DsVenc < 0 && floatval($c->saldo) > 0) ? 3 : 1
                    'DsVenc'    => $DsVenc,
                    'estado_credito'    => 3
                ];
            }else {
                if ($diferenciaDias > 7 ) {
                    $Creditos_mora[$key] = [
                        'id_creditos'       => $c->id_creditos,
                        'diferenciaDias' => $DsVenc,
                        'estado_credito'    => 2
                    ];
                }
            }
        
        }

        $array_merge = array_merge($Creditos_mora, $Creditos_venc);

        \Batch::update($batch_Credito, $Creditos_mora, $batch_index);        
        \Batch::update($batch_Credito, $Creditos_venc, $batch_index);

        \Log::channel('log_calc_Estados')->info("Se calculo los estados para el dia de  ".date('Y-m-d H:i:s'));

        return response()->json($array_merge);

    }
}