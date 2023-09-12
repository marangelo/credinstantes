<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Clientes;
use App\Models\Municipios;
use App\Models\Departamentos;
use App\Models\DiasSemana;
use App\Models\Abono;
use App\Models\Credito;

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
    public function getReporte()
    {           
        return view('Clientes.ls_reports');
        
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
        $response = Credito::SaveNewCredito($request);
        
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

    public function AddCredito(Request $request)
    {
        $response = Credito::AddCredito($request);
        
        return response()->json($response);
    }


}  