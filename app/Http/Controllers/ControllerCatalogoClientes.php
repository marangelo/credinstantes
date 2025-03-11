<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;


use App\Models\Clientes;
use App\Models\Zonas;


class ControllerCatalogoClientes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function ViewCatalogoClientes()
    {         
        $Titulo = "Catalogo de Clientes";
        $Clientes = Clientes::whereIn('activo', [1])->get();
        $Zonas       = Zonas::getZonas(); 
        return view('ClientesCatalogo.Table', compact('Titulo', 'Clientes','Zonas'));
        
    }

    public function FormClientes($id)
    {
        $Titulo = "Editar Cliente";
        $Cliente = Clientes::where('id_clientes',$id)->first();  
    
        return view('ClientesCatalogo.FormClientes', compact('Cliente','Titulo'));
    }
    public function AddCliente()
    {   
        $Titulo = "Nuevo Cliente";
        return view('ClientesCatalogo.FormClientes', compact('Titulo'));
    }

}