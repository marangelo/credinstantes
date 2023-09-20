<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Clientes;
use App\Models\Municipios;
use App\Models\Departamentos;
use App\Models\DiasSemana;
use App\Models\Abono;
use App\Models\Credito;
use App\Models\Credinstante;
use CodersFree\Date\Date;

class CredinstanteController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getDashboard()
    {          
        $Titulo = "Dashboard";
        return view('Dashboard.home',compact('Titulo'));
        
    }

    public function prtVoucher($Id){
        $Abono    = Abono::find($Id); 
        return view('Voucher.home', compact('Abono'));
    }
    
    public function getClientes()
    {   
        $Clientes    = Clientes::getClientes();  
        $Municipios  = Municipios::getMunicipios();  
        $DiasSemana  = DiasSemana::getDiasSemana();  
        $Titulo      = "Clientes";
        
        return view('Clientes.ls_Clientes', compact('Clientes','Municipios','DiasSemana','Titulo'));
        
    }

    public function getPerfil($id)
    {   
        
        $perfil_cliente = Clientes::find($id);  
        $DiasSemana     = DiasSemana::getDiasSemana();
        $Titulo         = "Perfil del Clientes";
    
        return view('Clientes.Perfil', compact('perfil_cliente','DiasSemana','Titulo'));
        
    }
    public function getMunicipios()
    {   
        $Municipios = Municipios::getMunicipios(); 
        $Departamentos = Departamentos::getDepartamentos();  
        $Titulo         = "Municipio";
        
        
        return view('Clientes.ls_Municipios', compact('Municipios','Departamentos','Titulo'));
        
    }
    public function getDepartamento()
    {   
        $Departamentos = Departamentos::getDepartamentos();  
        $Titulo         = "Departamento";
        
        return view('Clientes.ls_Departamentos', compact('Departamentos','Titulo'));
        
    }
    public function getDiasSemna()
    {   
        $DiasSemana = DiasSemana::getDiasSemana();  
        $Titulo         = "Dia de Semana";
        
        return view('Clientes.ls_DiasSemana', compact('DiasSemana','Titulo'));
        
    }
    public function getUsuarios ()
    {          
        return view('Usuario.lista');
        
    }

    public function SaveNewCredito(Request $request)
    {
        $response = Credito::SaveNewCredito($request);
        
        return response()->json($response);
    }

    public function editClient(Request $request)
    {
        $response = Clientes::editClient($request);
        
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

    public function AddCredito(Request $request)
    {
        $response = Credito::AddCredito($request);
        
        return response()->json($response);
    }

    public function rmDiaSemana($id)
    {
        $response = DiasSemana::rmDiaSemana($id);
        
        return response()->json($response);
    }

    public function SaveNewAbono(Request $request)
    {
        $response = Abono::SaveNewAbono($request);
        
        return response()->json($response);
    }

    public function getHistoricoAbono($IdCredito)
    {
        $Abonos =  Abono::getHistorico($IdCredito);

        return response()->json($Abonos);
    }

    public function Remover(Request $request)
    {
        $response = Credinstante::Remover($request);
        
        return response()->json($response);
    }
    public function rmAbono(Request $request)
    {
        $response = Abono::rmAbono($request);
        
        return response()->json($response);
    }

    public function creditCheck(Request $request)
    {
        $response = Clientes::creditCheck($request);
        
        return response()->json($response);
    }

    


}  