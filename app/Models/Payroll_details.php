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
                
                $Comision           = $request->input('payroll_comision_');
                $DiasTrab           = $request->input('payroll_dias_trabajados_');                
                $SalarioMensual     = $request->input('salario_mensual_');

                $NetoPagar  = $Comision + $SalarioMensual;
                $Vacaciones = ( $NetoPagar / 30 ) * 2.5 ;
                $Aguinaldo  = ( $NetoPagar / 12 ) ;
                $Indenizacion = ( $NetoPagar / 12 ) ;
            
                $response =  Payroll_details::where('id_payroll_details',  $NumRow)->update([
                    "dias_trabajados"   => $DiasTrab,
                    "comision"          => $Comision,
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
}