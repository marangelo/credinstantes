<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\GastosOperaciones;
use App\Traits\CheckUserLock;

class GastosOperacionesController extends Controller 
{
    use CheckUserLock;
    public function __construct()
    {
        $this->middleware('auth');
        // Luego verificar si el usuario está bloqueado
        $this->middleware(function ($request, $next) {
            $response = $this->checkUserLock();
            if ($response) {
                return $response; 
            }
            return $next($request);
        });
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
    public function getGasto(Request $request)
    {
        $response = GastosOperaciones::getGasto($request);
        return response()->json($response);
    }

}