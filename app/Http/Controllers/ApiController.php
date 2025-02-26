<?php
namespace App\Http\Controllers;
use GMP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Credito;
use App\Models\EstadosMonitor;
use App\Models\Clientes;
use Carbon\Carbon;
use Mavinoo\Batch\Batch;

use DateTime;
use DateInterval;

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
    public function SetTelefonos() 
    {
        $Cliente        = Clientes::all();
        $objClientes    = new Clientes;
        
        $ArrayClientes  = [] ;
        $StringRemove   = array('+', '_', '-', " ");

        foreach ($Cliente as $k => $v) {    
            
            $NewPhone = str_replace($StringRemove, '', $v->telefono);        
            $NewPhone = (strlen($NewPhone) > 8) ? substr($NewPhone, 3) : $NewPhone ;

            $ArrayClientes[$k] = [
                'id_clientes'    => $v->id_clientes,
                //'oldTelefono'   =>  $v->telefono,
                'telefono'      => $NewPhone,
            ];
        }
        
        \Batch::update($objClientes, $ArrayClientes, 'id_clientes');

        return response()->json($ArrayClientes);
        
    }

    public function CalcularFeriados()
    {
        $FechaOpen             = '2025-12-04';
        $Cuotas_               = 7;
        $IdCredito             = 1;
        $Fecha_abonos          = [];

        $fecha = new DateTime($FechaOpen);

        $year = date('Y');

        for ($i = 1; $i <= $Cuotas_; $i++) {
            $fecha->add(new DateInterval('P1W')); // Sumar una semana

            $Fecha_pago = Credito::isHoliday($fecha);

            
            $Fecha_abonos[] = [
                'id_creditos' => $IdCredito,
                'numero_pago' => $i, 
                'FechaPago' => $Fecha_pago
            ];
        }
        

        return response()->json($Fecha_abonos, 200);
    }
}