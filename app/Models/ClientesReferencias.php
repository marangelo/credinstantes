<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class ClientesReferencias extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "tbl_clientes_ref";
    protected $primaryKey = 'id_referencia';

    protected $fillable = [
        'id_referencia','id_cliente','id_req','nombre_ref','direccion_ref','telefono_ref'
    ];

    public static function rmReferencia(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Id           = $request->input('id_');
                $response =  ClientesReferencias::where('id_referencia',  $Id)->delete();
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
}