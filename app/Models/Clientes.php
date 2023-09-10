<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clientes extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Tbl_Clientes";
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

        if ($request->ajax()) {
            try {
                $id_municipio           = $request->input('Municipio_');
                $nombre                 = $request->input('Nombre_');
                $apellidos              = $request->input('Apellido_');
                $direccion_domicilio    = $request->input('Dire_');
                $cedula                 = $request->input('Cedula_');
                $telefono               = $request->input('Tele_');
                $score                  = 100;
                $activo                 = 1;


                $datos_a_insertar[0]['id_municipio']         = $id_municipio;
                $datos_a_insertar[0]['nombre']               = $nombre;
                $datos_a_insertar[0]['apellidos']            = $apellidos;
                $datos_a_insertar[0]['direccion_domicilio']  = $direccion_domicilio;
                $datos_a_insertar[0]['cedula']               = $cedula;
                $datos_a_insertar[0]['telefono']             = $telefono;
                $datos_a_insertar[0]['score']                = $score;
                $datos_a_insertar[0]['activo']               = $activo;

                $response = Clientes::insert($datos_a_insertar); 

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }
}
