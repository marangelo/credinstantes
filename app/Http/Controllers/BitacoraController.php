<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Bitacora;
use DB;

class BitacoraController extends Controller 
{ 
    public function Bitacora()
        {         
            $Titulo      = "Bitacora";
            $Usuarios    = DB::table('view_user')->whereNotIn('id_rol', [1])->get();
            return view('Bitacora.Desembolsos', compact('Titulo','Usuarios'));
        }
    public function getBitacora(Request $request)
    {
        $response = Bitacora::getBitacora($request);
        
        return response()->json($response);
    }
    public function ExportarBitacora(Request $request)
    {
        $response = Bitacora::Export($request);
    }
    public function UpdateSeguro(Request $request)
    {
        $response = Bitacora::UpdateSeguro($request);
        return response()->json($response);
    }
}