<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Municipios extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "dbo.Cat_Municipio";

    public function getDepartamentos()
    {
        return $this->hasOne(Departamentos::class, 'id_departamento','id_departamento');
    }

    public static function getMunicipios()
    {
        return Municipios::where('activo',1)->get();
    }
    public static function SaveNewMunicipio(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                
                $nombre_municipio           = $request->input('Nombre_');
                $Departamento               = $request->input('Departamento');

                $mun                        = new Municipios();
                $mun->nombre_municipio      = $nombre_municipio;
                $mun->id_departamento       = $Departamento;
                $response = $mun->save();

                return $response ;
                
                

            });
        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";

            dd($e->getMessage());

            return $mensaje;
        } 
    }
}
