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
        $Creditos         = [];
        
        $batch_Credito    = new Credito;
        $batch_index      = 'id_creditos';

        $Creditos_Estados = EstadosMonitor::Where('SALDO_CREDITO', '>', 0)->Where('CLIENTE_ACTIVO', 1)->get();            

        foreach ($Creditos_Estados as $key => $c) {
            $Estado = 1 ;
            $Estado = ($c->MORA == 'S') ? 2 : $Estado ;
            $Estado = ($c->VENCIDO == 'S') ? 3 : $Estado ;
            $Creditos[$key] = [
                'id_creditos'       => $c->ID_CREDITO,
                'estado_credito'    => $Estado
            ];

        }

      

        \Batch::update($batch_Credito, $Creditos, $batch_index);

        \Log::channel('log_calc_Estados')->info("Se calculo los estados para el dia de  ".date('Y-m-d H:i:s'));

        return response()->json($Creditos);

    }
}