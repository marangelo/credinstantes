<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Credito extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Tbl_Creditos";    
    protected $primaryKey = 'id_creditos';

    public function abonos()
    {
        return $this->hasMany(Abono::class, 'id_creditos', 'id_creditos')->where('activo',1)->orderBy('id_abonoscreditos', 'desc')->limit(1);
    }
    
    public function abonosCount()
    {
        return $this->hasMany(Abono::class, 'id_creditos', 'id_creditos')->where('activo',1)->count();
    }
    public function Estado()
    {
        return $this->hasOne(Estados::class, 'id_estados','estado_credito');
    }

    public function getAbonos()
    {
        return $this->hasMany(Abono::class, 'id_creditos','id_creditos')->where('activo',1);
    }


    public static function getCreditos()
    {
        return Credito::where('activo',1)->get();
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
                $Estado                 = 1;

                $DiaSemana_         = $request->input('DiaSemana_');
                $Monto_             = $request->input('Monto_');
                $Plato_             = $request->input('Plato_');
                $Interes_           = $request->input('Interes_');
                $Total_             = $request->input('Total_');
                $Cuotas_             = $request->input('Cuotas_');

                $vlCuota           = $request->input('vlCuota');
                $vlInteres             = $request->input('vlInteres');
                $Saldos_             = $request->input('Saldos_');
                $InteresesPorCuota             = $request->input('InteresesPorCuota');


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
                    'intereses_por_cuota'=>$InteresesPorCuota,
                    'saldo'               => $Saldos_,
                    'estado_credito'               => $Estado,
                    'activo'            => 1
                ];
                $response = Credito::insert($datos_credito);
                


                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }
    public static function AddCredito(Request $request)
    {

        if ($request->ajax()) {
            try {

                $idInsertado        = $request->input('IdClientes');

                $DiaSemana_         = $request->input('DiaSemana_');
                $Monto_             = $request->input('Monto_');
                $Plato_             = $request->input('Plato_');
                $Interes_           = $request->input('Interes_');
                $Total_             = $request->input('Total_');
                $Cuotas_            = $request->input('Cuotas_');

                $vlCuota            = $request->input('vlCuota');
                $vlInteres          = $request->input('vlInteres');
                $Saldos_             = $request->input('Saldos_');
                $InteresesPorCuota             = $request->input('InteresesPorCuota');

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
                    'intereses_por_cuota'=>$InteresesPorCuota,
                    'estado_credito'               => 1,
                    'activo'               => 1,
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
