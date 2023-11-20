<?php
namespace App\Http\Controllers;
use GMP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Credito;
use App\Models\EstadosMonitor;
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

        $Creditos_Estados = EstadosMonitor::Where('SALDO_CREDITO', '>', 0)->Where('CLIENTE_ACTIVO', $idEstado)->get();            

        foreach ($Creditos_Estados as $key => $c) {
            if ($c->VENCIDO === 'S' ) {
                $Creditos_venc[$key] = [
                    'id_creditos'       => $c->ID_CREDITO,
                    'estado_credito'    => 3
                ];
            }else {
                if ($c->MORA === 'S' ) {
                    $Creditos_mora[$key] = [
                        'id_creditos'       => $c->ID_CREDITO,
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