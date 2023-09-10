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
    public function getCreditos()
    {
        return $this->hasMany(Credito::class, 'id_clientes','id_clientes');;
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

                $DiaSemana_         = $request->input('DiaSemana_');
                $Monto_             = $request->input('Monto_');
                $Plato_             = $request->input('Plato_');
                $Interes_           = $request->input('Interes_');
                $Total_             = $request->input('Total_');
                $Cuotas_             = $request->input('Cuotas_');

                $vlCuota           = $request->input('vlCuota');
                $vlInteres             = $request->input('vlInteres');
                $Saldos_             = $request->input('Saldos_');


                $datos_a_insertar = [
                    'id_municipio'         => $id_municipio,
                    'nombre'               => $nombre,
                    'apellidos'            => $apellidos,
                    'direccion_domicilio'  => $direccion_domicilio,
                    'cedula'               => $cedula,
                    'telefono'             => $telefono,
                    'score'                => $score,
                    'activo'               => $activo,
                ];
                $idInsertado = Clientes::insertGetId($datos_a_insertar);

                $datos_credito = [
                    'creado_por'          => Auth::id(),
                    'fecha_apertura'      => date('Y-m-d H:i:s'),
                    'id_diassemana'       => $DiaSemana_,
                    'id_clientes'         => $idInsertado,
                    'monto_credito'       => $Monto_,
                    'plazo'               => $Plato_,
                    'taza_interes'        => $Interes_,
                    'numero_cuotas'       => $Cuotas_,
                    'total'               => $Total_,
                    'cuota'               => $vlCuota,
                    'interes'             => $vlInteres,
                    'saldo'               => $Saldos_,
                ];
                $response = Credito::insert($datos_credito);
                


                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }
}
