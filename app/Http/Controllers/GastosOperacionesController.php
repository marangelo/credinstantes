<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\GastosOperaciones;

class GastosOperacionesController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Gastos()
    {         
        $Titulo      = "Gastos Operaciones";
        return view('GastosOperaciones.Home', compact('Titulo'));
    }
    public function getGastosOperaciones(Request $request)
    {
        $response = GastosOperaciones::getGastosOperaciones($request);
        
        return response()->json($response);
    }
    
    public function SaveGastoOperaciones(Request $request)
    {
        $response = GastosOperaciones::SaveGastoOperaciones($request);
        
        return response()->json($response);
    }
    public function RemoveGasto(Request $request)
    {
        $response = GastosOperaciones::RemoveGasto($request);
        return response()->json($response);
    }
    public function ExportGastos(Request $request)
    {
        $response = GastosOperaciones::ExportGastos($request);
    }

}