<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitudes;
use App\Models\Zonas;
use App\Models\DiasSemana;
use App\Models\Usuario;
use App\Models\Municipios;

class SolicitudesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ViewNuevos(){
        $Titulo      = "SOLICTUDES";
        $lblBtn      = "NUEVA";
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

    public function ViewForm($Formulario){
        $Titulo      = "FORMULARIO";        
        $Zonas       = Zonas::getZonas(); 
        $DiasSemana  = DiasSemana::getDiasSemana();
        $Promo       = Usuario::where('activo','S')->get(); 
        $Municipios  = Municipios::getMunicipios();  
        return view('Solicitudes.Form', compact('Titulo', 'Zonas', 'DiasSemana', 'Promo', 'Municipios'));

    }
    public function getSolicitudes(Request $request)
    {
        $response = Solicitudes::getProspectos($request);
        
        return response()->json($response);
    }
    
}