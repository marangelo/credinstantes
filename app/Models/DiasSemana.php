<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class DiasSemana extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Cat_DiasSemana";

    public static function getDiasSemana()
    {
        return DiasSemana::where('activo',1)->get();
    }

    public static function rmDiaSemana($id)
    {
        try {
                
            $delete = DiasSemana::where('id_diassemana', $id )->delete();
            return  $delete;               
        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
            return response()->json($mensaje);
        }
    }
    public static function AddDiaSemana(Request $request)
    {
        if ($request->ajax()) {
            try {
                $dia_semana           = $request->input('Nombre_');

                $datos_a_insertar[0]['dia_semana']           = $dia_semana;
                $datos_a_insertar[0]['dia_semananum']            = 1;

                $response = DiasSemana::insert($datos_a_insertar); 

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
}
