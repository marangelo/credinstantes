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
use App\Models\Clientes;

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
        $Origin      = "Nueva";

        return view('Solicitudes.Form', compact('Titulo', 'Zonas', 'DiasSemana', 'Promo', 'Municipios', 'Request','Origin'));

    }
    public function ViewFormRenovaciones($IdReq){

        $Cliente = ($IdReq > 0 ) ? Clientes::find($IdReq) : [] ;
        
        
        $Titulo      = "SOLIC. RENOVACION";        
        $Zonas       = Zonas::getZonas(); 
        $DiasSemana  = DiasSemana::getDiasSemana();
        $Promo       = Usuario::where('activo','S')->get(); 
        $Municipios  = Municipios::getMunicipios();  
        $Origin      = "Renovacion";

        return view('Solicitudes.RequestRenovacion', compact('Titulo', 'Zonas', 'DiasSemana', 'Promo', 'Municipios', 'Cliente','Origin'));

    }
    public function getSolicitudes(Request $request)
    {
        $response = RequestsCredit::getRequestsCredit($request);
        
        return response()->json($response);
    }

    public function UpRequestCredit(Request $request)
    {

        $IdProspecto           = $request->input('IdProspecto_'); 
        $TypoSolicitud         = $request->input('_Origin'); 

        if($TypoSolicitud === "Renovacion"){
            $response = Credito::AddCredito($request);    
        }else{
            $response = Credito::SaveNewCredito($request);   
        }
    
        RequestsCredit::UpdateEstadoRequest($IdProspecto);  

        
        
        return response()->json([
            'message' => 'Credito Guardado Correctamente.',
        ], 201);

    }

    public function getClientesInactivos(Request $request)
    {
        $Clientes_Inactivos    = Clientes::getInactivos(0);  
        return response()->json([
            'message' => 'Clientes Inactivos.',
            'Clientes_Inactivos' => $Clientes_Inactivos
        ], 201);
    }

    public static function RemoverRequest(Request $request)
    {
        $IdRequest           = $request->input('Idrequest');

        $respuest = RequestsCredit::UpdateEstadoRequest($IdRequest);

      

        return response()->json([
            'message' => 'Registro Removido Correctamente.',
        ], 201);
    }
    
}