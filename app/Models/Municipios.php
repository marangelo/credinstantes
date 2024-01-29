<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Municipios extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "cat_municipio";

    public function getDepartamentos()
    {
        return $this->hasOne(Departamentos::class, 'id_departamento','id_departamento');
    }

    public static function getMunicipios()
    {
        return Municipios::where('activo',1)->get();
    }
    public static function rmMunicipio($id)
    {
        try {
                
            $delete = Municipios::where('id_municipio', $id )->delete();
            return  $delete;               
        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
            return response()->json($mensaje);
        }
    }
    public static function SaveNewMunicipio(Request $request)
    {
        if ($request->ajax()) {
            try {
                $nombre_municipio           = $request->input('Nombre_');
                $Departamento               = $request->input('Departamento');

                $datos_a_insertar[0]['nombre_municipio']           = $nombre_municipio;
                $datos_a_insertar[0]['id_departamento']            = $Departamento;

                $response = Municipios::insert($datos_a_insertar); 

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
}
