<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class ClientesGarantia extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "tbl_clientes_garantias";
    protected $primaryKey = 'id_garantia';

    protected $fillable = [
        'id_garantia','id_cliente','detalle_articulo','marca','color','valor_recomendado'
    ];

    public static function rmGarantia(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Id           = $request->input('id_');
                $response =  ClientesGarantia::where('id_garantia',  $Id)->delete();
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
}