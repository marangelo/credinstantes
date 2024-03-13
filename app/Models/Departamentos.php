<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Departamentos extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "cat_departamento";

    public static function getDepartamentos()
    {
        return Departamentos::all();
    }
    public static function rmDepartamento($id)
    {
        try {
                
            $delete = Departamentos::where('id_departamento', $id )->delete();
            return  $delete;               
        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
            return response()->json($mensaje);
        }
    }
    public static function SaveNewDepartamento(Request $request)
    {
        if ($request->ajax()) {
            try {
                $nombre_departamento    = $request->input('Nombre_');
                $activo                 = 1;

                $datos_a_insertar[0]['nombre_departamento']     = $nombre_departamento;
                $datos_a_insertar[0]['activo']                  = $activo;

                $response = Departamentos::insert($datos_a_insertar); 

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
}
