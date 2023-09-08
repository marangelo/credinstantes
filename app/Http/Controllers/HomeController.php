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
        //$this->middleware('auth');
    }
    public function getDashboard()
    {          
        return view('Dashboard.home');
        
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
        
        return view('Clientes.Perfil', compact('perfil_cliente'));
        
    }
    public function getMunicipios()
    {   
        $Municipios = Municipios::getMunicipios();  
        
        return view('Clientes.ls_Municipios', compact('Municipios'));
        
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

}  