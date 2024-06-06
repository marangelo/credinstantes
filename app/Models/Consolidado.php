<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Consolidado extends Model {
    public $timestamps = false;
    protected $table = "tbl_consolidados";

    //CREA UNA FUNCIONA STATIC PARA LEER UN PROCEDURE QUE SE LLAMA CalcConsolidado Y RECIBE COMO PARAMETRO EL YEAR ACTUAL
    public static function CalcConsolidado($year){
        try {
            $json_arrays = array();
            $i = 0 ;

            $months = array(
                //'Jan', 'Feb', 'Mar', 'Apr', 'May', 
                'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            );
            foreach($months as $m){
                $json_arrays['header_date'][$i] = $m . ' ' . substr($year, -2);
                $i++;
            }
        
            $Rows = \DB::select('CALL CalcConsolidado(?)', array($year));

            foreach($Rows as $r){
                $json_arrays['header_date_rows'][$i]['CONCEPTO'] = $r->Concepto;
                foreach($json_arrays['header_date'] as $dtFecha => $valor){
                    
                    $rows_in = date("M", strtotime($valor)) . ltrim(date("y", strtotime($valor)), '0');

                    $json_arrays['header_date_rows'][$i][$rows_in] = ($r->$rows_in=='0.0' || $r->$rows_in=='00.00') ? '0.00' : number_format($r->$rows_in,2)  ;
    
                }
                $i++;
            }


            return $json_arrays;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
