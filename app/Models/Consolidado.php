<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Consolidado extends Model {
    public $timestamps = false;
    protected $table = "tbl_consolidados";

    public function ConsolidadoCategoria()
    {
        return $this->hasMany(ConsolidadoCat::class, 'key', 'Concepto');
    }

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
                $json_arrays['header_date'][$i] = $m . substr($year, -2);
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

    public static function getIndicadores(Request $request)
    {
        $dtEnd  = $request->input('dtEnd').' 00:00:00';
        $array  = array();
        
        $Indicadores = ConsolidadoCat::WhereIn('id_cat_consolidado',[8,9,12,13,14])->get()->toArray();

        $Obj =  Consolidado::whereMonth('Fecha', '=', date('m', strtotime($dtEnd)))
                            ->whereIn('Concepto', array_column($Indicadores, 'key'))
                            ->get();

        foreach ($Obj as $key => $a) {  
            $array[$key] = [
                "Id"            => $a->id_consolidado,
                "Fecha_gasto"   => \Date::parse($a->Fecha)->format('d-m-Y') ,
                "Concepto"      => $a->ConsolidadoCategoria[0]->descripcion,
                "Monto"         => $a->Valor,
                "Usuario"       =>'',
            ];
                
        }
        return $array;
    }
    public static function SaveIndiador(Request $request)
    {
        if ($request->ajax()) {
            try {

                $existingRecord = Consolidado::where('Concepto', $request->input('_Indicador'))
                    ->where('Fecha', $request->input('_Fecha'))
                    ->exists();

                if ($existingRecord) {
                    $response = Consolidado::where('Concepto', $request->input('_Indicador'))
                    ->where('Fecha', $request->input('_Fecha'))
                    ->update([
                        'Concepto'  => $request->input('_Indicador'),
                        'Fecha'     => $request->input('_Fecha'),
                        'Valor'     => $request->input('_Monto'),
                    ]);
                } else {
                    $response = Consolidado::insert([
                        'Concepto'  => $request->input('_Indicador'),
                        'Fecha'     => $request->input('_Fecha'),
                        'Valor'     => $request->input('_Monto'),
                    ]);
                }

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function RemoveIndicador(Request $request)
    {
        if ($request->ajax()) {
            try {
                $ID         = $request->input('IdIndicador');
                
                $response =   Consolidado::where('id_consolidado',  $ID)->delete();
    
                return $response;
    
    
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function getInfoIndicador(Request $request)
    {
        $ID = $request->input('IdConso');
        $Obj = Consolidado::where('id_consolidado', $ID)->get();
        $array_gasto_ope = array();
        foreach ($Obj as $key => $a) {
            $array_gasto_ope = [
                "Id" => $a->id_consolidado,
                "Fecha" => \Date::parse($a->Fecha)->format('d/m/Y'),
                "Concepto" => $a->Concepto,
                "Monto" => $a->Valor,
            ];
        }
        return $array_gasto_ope;
    }
}
