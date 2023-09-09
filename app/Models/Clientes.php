<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clientes extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "dbo.Tbl_Clientes";
    protected $primaryKey = 'id_clientes';

    public function getMunicipio()
    {
        return $this->hasOne(Municipios::class, 'id_municipio','id_municipio');
    }

    public static function getClientes()
    {
        return Clientes::limit(5)->orderBy('id_clientes', 'desc')->get();
    }

    public static function SaveNewCredito(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                
                $id_municipio           = $request->input('Municipio_');
                $nombre                 = $request->input('Nombre_');
                $apellidos              = $request->input('Apellido_');
                $direccion_domicilio    = $request->input('Dire_');
                $cedula                 = $request->input('Cedula_');
                $telefono               = $request->input('Tele_');
                $score                  = 100;
                $activo                 = 1;

                $cln                        = new Clientes();
                $cln->id_municipio          = $id_municipio;
                $cln->nombre                = $nombre;
                $cln->apellidos             = $apellidos;
                $cln->direccion_domicilio   = $direccion_domicilio;
                $cln->cedula                = $cedula;
                $cln->telefono              = $telefono;
                $cln->score                 = $score;
                $cln->activo                = $activo;
                //$cln->USUARIO             = Auth::id();
                $response = $cln->save();

                return $response ;
                

            });
        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";

            return $mensaje;
        } 
    }
}
