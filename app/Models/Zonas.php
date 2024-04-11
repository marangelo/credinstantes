<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Zonas extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "cat_zona";

    public static function getZonas()
    {
        return Zonas::where('activo',1)->get();
    }
    public static function rmZona($id)
    {
        try {
                
            $delete = Zonas::where('id_zona', $id )->delete();
            return  $delete;               
        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
            return response()->json($mensaje);
        }
    }
    public static function addZona(Request $request)
    {
        if ($request->ajax()) {
            try {
                $nombre_departamento    = $request->input('Nombre_');
                $activo                 = 1;

                $datos_a_insertar[0]['nombre_zona']     = $nombre_departamento;
                $datos_a_insertar[0]['activo']                  = $activo;

                $response = Zonas::insert($datos_a_insertar); 

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }

    public function UsuarioCobrador()
    {
        return $this->hasOne(Usuario::class, 'id_zona','id_zona')->where('id_rol',2)->where('activo','S');
    }
}
