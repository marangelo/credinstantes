<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zonas;
use App\Models\Prospectos;


class ProspectosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ProspectosView(){
        $Titulo      = "Clientes Prospectos";
        $Zonas       = Zonas::getZonas();
        return view('Clientes_Prospectos.Home', compact('Titulo','Zonas'));

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
        $response = Prospectos::DeleteProspecto($IdProspecto);        
        
        if($response){
            return redirect()->route('Prospectos');
        }
    }

}