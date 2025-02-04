<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitudes;
use App\Models\Zonas;
use App\Models\DiasSemana;
use App\Models\Usuario;
use App\Models\Municipios;
use App\Models\RequestsCredit;
use App\Models\Credito;

class SolicitudesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ViewNuevos(){
        $Titulo      = "SOLICTUDES";
        $lblBtn      = "NUEVAS";
        $Zonas       = Zonas::getZonas(); 
        $DiasSemana  = DiasSemana::getDiasSemana();
        $Promo       = Usuario::where('activo','S')->get(); 
        $Municipios  = Municipios::getMunicipios();  
        return view('Solicitudes.Table', compact('Titulo', 'Zonas', 'DiasSemana', 'Promo', 'Municipios', 'lblBtn'));

    }
    public function ViewRenovaciones(){
        $Titulo      = "SOLICITUDES";
        $lblBtn      = "RENOVAR";
        $Zonas       = Zonas::getZonas(); 
        $DiasSemana  = DiasSemana::getDiasSemana();
        $Promo       = Usuario::where('activo','S')->get(); 
        $Municipios  = Municipios::getMunicipios();  
        return view('Solicitudes.Table', compact('Titulo', 'Zonas', 'DiasSemana', 'Promo', 'Municipios', 'lblBtn'));

    }

    public function ViewForm($IdReq){

        $Request = ($IdReq > 0 ) ? RequestsCredit::find($IdReq) : [] ;
        
        $Titulo      = "FORMULARIO";        
        $Zonas       = Zonas::getZonas(); 
        $DiasSemana  = DiasSemana::getDiasSemana();
        $Promo       = Usuario::where('activo','S')->get(); 
        $Municipios  = Municipios::getMunicipios();  

        return view('Solicitudes.Form', compact('Titulo', 'Zonas', 'DiasSemana', 'Promo', 'Municipios', 'Request'));

    }
    public function getSolicitudes(Request $request)
    {
        $response = RequestsCredit::getRequestsCredit($request);
        
        return response()->json($response);
    }

    public function UpRequestCredit(Request $request)
    {

        $IdProspecto           = $request->input('IdProspecto_'); 
        
        RequestsCredit::UpdateEstadoRequest($IdProspecto);  

        $response = Credito::SaveNewCredito($request);      
        
        return response()->json([
            'message' => 'Credito Guardado Correctamente.',
        ], 201);

    }
    
}