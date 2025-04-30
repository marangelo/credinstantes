<?php

namespace App\Models;
use Auth;
use Session; 
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClientesNA extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "tbl_clientes_na";
    protected $primaryKey = 'id_cliente_na';

    protected $fillable = [
        'id_cliente_na','id_cliente','update_at'
    ];

    public static function ArchivarClient(Request $request)
    {
        $id_cliente = $request->input('Cliente_');
        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'No se pudo archivar el cliente.';
        
        try {
            DB::beginTransaction();
            
            // Verifica si el cliente ya está archivado
            $clienteExistente = ClientesNA::where('id_cliente', $id_cliente)->first();
            if (!$clienteExistente) {
                // Si no existe, crea un nuevo registro
                $clienteNA = new ClientesNA();
                $clienteNA->id_cliente = $id_cliente;
                $clienteNA->update_at = date('Y-m-d H:i:s');
                $clienteNA->save();
                
                DB::commit();
                $response['status'] = 'success';
                $response['message'] = 'Cliente archivado correctamente.';
            } else {
                DB::rollback();
                $response['message'] = 'El cliente ya está archivado.';
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response['message'] = 'Error al archivar el cliente: ' . $e->getMessage();
        }
        
        return $response;
    }
    public static function UnArchivarClient(Request $request)
    {
        $id_cliente = $request->input('Cliente_');
        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'No se pudo desarchivar el cliente.';
        
        try {
            DB::beginTransaction();
            
            // Verifica si el cliente ya está archivado
            $clienteExistente = ClientesNA::where('id_cliente', $id_cliente)->first();
            if ($clienteExistente) {
                // Si existe, elimina el registro
                $clienteExistente->delete();
                
                DB::commit();
                $response['status'] = 'success';
                $response['message'] = 'Cliente desarchivado correctamente.';
            } else {
                DB::rollback();
                $response['message'] = 'El cliente no está archivado.';
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response['message'] = 'Error al desarchivar el cliente: ' . $e->getMessage();
        }
        
        return $response;
    }
}