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
        $datos_a_creditos = [];
        $idEstado         = 1; 
        $DsVenc           = 0;
        $batch_Credito    = new Credito;
        $batch_index      = 'id_creditos';
        //ENTIENDA ACTIVO COMO QUE EL CREDITO NO SE A REMOVIDO
        $Creditos_Activos =  Credito::getCreditos();        

            foreach ($Creditos_Activos as $key => $c) {
                //VERIFICA SI EL ULTIMO ABONO APLCIADO EXCEDE A LA FECHA ACTUAL
                $last_abono = ($c->abonos->isNotEmpty()) ? $c->abonos->first()->fecha_cuota : $c->fecha_apertura ;
                $date_refe_abono = $c->refAbonos->first()->FechaPago;

                //CALCULA SI LA CUENTA ESTA EN VECIMIENTO
                $fechaDada      = Carbon::parse($last_abono);
                $fechaActual    = Carbon::now();
                $diferenciaDias = $fechaDada->diffInDays($fechaActual);

                //CALCULA SI LA CUENTA SE ENCUENTRA VENCINA
                $dtEnds         = Carbon::parse($date_refe_abono);
                $dtNow          = Carbon::now();
                $DsVenc         = $dtNow->diffInDays($dtEnds,false);

                $idEstado = ($diferenciaDias > 7 ) ? 2 : 1 ;
                
                //$idEstado = ($DsVenc < 0 && floatval($c->saldo) > 0) ? 3 : 1 ;

                $datos_a_creditos[$key] = [
                    'id_creditos'       => $c->id_creditos,
                    'estado_credito'    => $idEstado
                ];
            
            }
        //NO HABIA NECESITAS DE UTILIZAR ESTA DEPENDENCIA, PERO QUEDA GUAPA EKISDE
        \Batch::update($batch_Credito, $datos_a_creditos, $batch_index);
        return response()->json($datos_a_creditos);

    }
}