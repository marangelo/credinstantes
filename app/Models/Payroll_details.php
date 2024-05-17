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


    public static function UpdatePayroll(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Comision       = $request->input('payroll_comision_');
                $DiasTrab       = $request->input('payroll_dias_trabajados_');
                $NumRow         = $request->input('payroll_num_row_');
            
                $response =  Payroll_details::where('id_payroll_details',  $NumRow)->update([
                    "dias_trabajados" => $DiasTrab,
                    "comision" => $Comision,
                ]);
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
}