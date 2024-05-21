<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class Payroll_details extends Model {
    protected $table = "tbl_payroll_details";
    protected $connection = 'mysql';
    protected $primaryKey = 'id_payroll_details';


    public static function UpdateQuincenal(Request $request)
    {
        if ($request->ajax()) {
            try {
                $NumRow             = $request->input('payroll_num_row_');
                
                $DiasTrab           = $request->input('payroll_dias_trabajados_');                
                $SalarioMensual     = $request->input('salario_mensual_');
                $payroll_inss       = $request->input('payroll_inss_');
                $payroll_ir         = $request->input('payroll_ir_');

                $SalarioQuiencenal  = ( $SalarioMensual / 30 ) * $DiasTrab;

                $NetoPagar =  $SalarioQuiencenal;
                
                //RESTAR DEDUCCIONES DE LEY
                $NetoPagar  = $NetoPagar - $payroll_inss - $payroll_ir;

                $Vacaciones = ( $SalarioQuiencenal / 15 ) * 2.5 ;
                $Aguinaldo  = ( $SalarioQuiencenal / 12 ) ;
                $Indenizacion = ( $SalarioQuiencenal / 12 ) ;
            
                $response =  Payroll_details::where('id_payroll_details',  $NumRow)->update([
                    "dias_trabajados"   => $DiasTrab,
                    'salario_quincenal' => $SalarioQuiencenal,
                    'neto_pagar'        => $NetoPagar,
                    'vacaciones'        => $Vacaciones,
                    'aguinaldo'         => $Aguinaldo,
                    'indenmnizacion'    => $Indenizacion,
                    'inss_patronal'     => $payroll_inss,
                    'ir'                => $payroll_ir,
                ]);
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function UpdateDepreciacion(Request $request)
    {
        if ($request->ajax()) {
            try {
                $NumRow             = $request->input('payroll_num_row_');
            
                $response =  Payroll_details::where('id_payroll_details',  $NumRow)->update([
                    'neto_pagar'    => $request->input('pago_depreciacion_'),
                    'concepto'      => $request->input('depreciacion_concepto_'),
                ]);
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function UpdateComisiones(Request $request)
    {
        if ($request->ajax()) {
            try {
                $NumRow             = $request->input('payroll_num_row_');
                $Comisiones           = $request->input('comisiones_');                

                $NetoPagar =  $Comisiones;

                $Vacaciones = ( $NetoPagar / 30 ) * 2.5 ;
                $Aguinaldo  = ( $NetoPagar / 12 ) ;
                $Indenizacion = ( $NetoPagar / 12 ) ;
            
                $response =  Payroll_details::where('id_payroll_details',  $NumRow)->update([
                    'comision'          => $Comisiones,
                    'neto_pagar'        => $NetoPagar,
                    'vacaciones'        => $Vacaciones,
                    'aguinaldo'         => $Aguinaldo,
                    'indenmnizacion'    => $Indenizacion,
                ]);
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
}