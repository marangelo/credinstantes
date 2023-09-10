<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Clientes;
use App\Models\Municipios;
use App\Models\Departamentos;
use App\Models\DiasSemana;

class HomeController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getDashboard()
    {          
        return view('Dashboard.home');
        
    }

    public function prtVoucher(){
        return view('Voucher.home');
    }
    public function getClientes()
    {   
        $Clientes    = Clientes::getClientes();  
        $Municipios  = Municipios::getMunicipios();  
        $DiasSemana  = DiasSemana::getDiasSemana();  
        
        return view('Clientes.ls_Clientes', compact('Clientes','Municipios','DiasSemana'));
        
    }

    public function getPerfil($id)
    {   
        
        $perfil_cliente = Clientes::find($id);  
        $DiasSemana = DiasSemana::getDiasSemana();
        
        return view('Clientes.Perfil', compact('perfil_cliente','DiasSemana'));
        
    }
    public function getMunicipios()
    {   
        $Municipios = Municipios::getMunicipios(); 
        $Departamentos = Departamentos::getDepartamentos();  
        
        
        return view('Clientes.ls_Municipios', compact('Municipios','Departamentos'));
        
    }
    public function getDepartamento()
    {   
        $Departamentos = Departamentos::getDepartamentos();  
        
        return view('Clientes.ls_Departamentos', compact('Departamentos'));
        
    }
    public function getDiasSemna()
    {   
        $DiasSemana = DiasSemana::getDiasSemana();  
        
        return view('Clientes.ls_DiasSemana', compact('DiasSemana'));
        
    }
    public function getUsuarios ()
    {          
        return view('Usuario.lista');
        
    }

    public function SaveNewCredito(Request $request)
    {
        $response = Clientes::SaveNewCredito($request);
        
        return response()->json($response);
    }

    public function SaveNewMunicipio(Request $request)
    {
        $response = Municipios::SaveNewMunicipio($request);
        
        return response()->json($response);
    }

    public function rmMunicipio($id)
    {
        $response = Municipios::rmMunicipio($id);
        
        return response()->json($response);
    }

    public function SaveNewDepartamento(Request $request)
    {
        $response = Departamentos::SaveNewDepartamento($request);
        
        return response()->json($response);
    }

    public function rmDepartamento($id)
    {
        $response = Departamentos::rmDepartamento($id);
        
        return response()->json($response);
    }

    public function AddDiaSemana(Request $request)
    {
        $response = DiasSemana::AddDiaSemana($request);
        
        return response()->json($response);
    }

    public function rmDiaSemana($id)
    {
        $response = DiasSemana::rmDiaSemana($id);
        
        return response()->json($response);
    }


}  