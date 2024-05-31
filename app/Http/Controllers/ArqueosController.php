<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Arqueo;
use App\Models\Zonas;
use App\Exports\ExportArqueo;
use App\Traits\CheckUserLock;

class ArqueosController extends Controller 
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

    public function ShowHome()
    {         
        $Titulo      = "Arqueos";
        $Zonas       = Zonas::getZonas(); 
        return view('Arqueos.Home', compact('Titulo','Zonas'));
    }
    public function ShowDetalles($ID)
    {         
        $Titulo     = "Arqueo Nuevo";
        $Arqueo     = Arqueo::find($ID); 
        $Cobrado    = Arqueo::Cobrado($ID);
    
        return view('Arqueos.Nuevo', compact('Titulo','Arqueo','Cobrado'));
    }

    public function UpdateRecuperado(Request $request)
    {
        $Cobrado    = Arqueo::AjaxCobrado($request);
        return response()->json($Cobrado);
    }
    public function Init($Zona)
    {         
        $InitArqueo         = Arqueo::InitArqueo($Zona);
        return response()->json($InitArqueo);
    }
    public function getDataArqueos(Request $request)
    {
        $response = Arqueo::getDataArqueos($request);
        
        return response()->json($response);
    }
    public function DataTableMoneda(Request $request)
    {
        $response = Arqueo::DataTableMoneda($request);
        
        return response()->json($response);
    }
    public function UpdateRowArqueo(Request $request)
    {
        $response = Arqueo::UpdateRowArqueo($request);
        
        return response()->json($response);
    }
    public function UpdateArqueo(Request $request)
    {
        $response = Arqueo::UpdateArqueo($request);
        
        return response()->json($response);
    }
    public function ExportArqueo($ID)
    {
        $response = Arqueo::Export($ID);
    }
    public function RemoveArqueo(Request $request)
    {
        $response = Arqueo::RemoveArqueo($request);
        return response()->json($response);
    }

}