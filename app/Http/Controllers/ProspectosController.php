<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zonas;
use App\Models\Prospectos;
use App\Models\Municipios;
use App\Models\DiasSemana;
use App\Models\Usuario;
use App\Models\Credito;


class ProspectosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ProspectosView(){
        $Titulo      = "Clientes Prospectos";
        $Municipios  = Municipios::getMunicipios();  
        $DiasSemana  = DiasSemana::getDiasSemana();
        $Zonas       = Zonas::getZonas();  
        $Promo       = Usuario::where('activo','S')->get(); 
        return view('Clientes_Prospectos.Home', compact('Titulo','Zonas','Municipios','DiasSemana','Promo'));

    }

    
    public function getProspectos(Request $request)
    {
        $response = Prospectos::getProspectos($request);
        
        return response()->json($response);
    }
    public function FormPospecto($IdProspecto){
        $Titulo      = "Clientes Prospectos";
        $Zonas       = Zonas::getZonas();
        $Prospecto   = Prospectos::where('id_prospecto', $IdProspecto)->first();
        
        return view('Clientes_Prospectos.Form', compact('Titulo','Prospecto','Zonas'));
    }
    public function SaveProspecto(Request $request)
    {
        $response = Prospectos::SaveProspecto($request);
        
        return response()->json($response);
    }
    public function DeleteProspecto($IdProspecto)
    {
        $response = Prospectos::UpdateEstadoProspecto($IdProspecto,0);        
        
        if($response){
            return redirect()->route('Prospectos');
        }
    }

    public function getInfoProspecto($IdProspecto)
    {
        $response = Prospectos::getInfoProspecto($IdProspecto);
        
        return response()->json($response);
    }
    public function SaveNewProspecto(Request $request)
    {
        $IdProspecto           = $request->input('IdProspecto_');

        Prospectos::UpdateEstadoProspecto($IdProspecto,2);  

        $response = Credito::SaveNewCredito($request);
        
        return response()->json($response);
    }

}