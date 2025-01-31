<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zonas;
use App\Models\Prospectos;
use App\Models\Municipios;
use App\Models\DiasSemana;
use App\Models\Usuario;
use App\Models\Credito;
use App\Models\Clientes;


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
        $cedula = $request->input('Cedula_');

        // Check if the prospect exists in the Prospectos model
        $prospecto = Prospectos::where('Cedula', $cedula)->first();

        // If not found in Prospectos, check in the Clientes model
        if (!$prospecto) {
            $cliente = Clientes::where('Cedula', $cedula)->first();
            if (!$cliente) {
                // If not found in Clientes, proceed to save the prospect
                $response = Prospectos::SaveProspecto($request);
                //return response()->json($response);
            } else {
                return response()->json(['exists' => true, 'message' => 'Prospecto Encontrado como Clientes.']);
            }
        }

        return response()->json(['exists' => $prospecto ? true : false, 'message' => $prospecto ? 'Prospecto encontrado.' : 'Registro Grabado Correctamente.']);


        
        //return response()->json($response);
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