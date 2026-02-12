<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Bitacora;
use App\Models\Zonas;

class BitacoraController extends Controller 
{ 
    public function Bitacora()
        {         
            $Titulo      = "Bitacora";
            $Zonas       = Zonas::getZonas();
            return view('Bitacora.Desembolsos', compact('Titulo','Zonas'));
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