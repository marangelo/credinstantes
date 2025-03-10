<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;


use App\Models\Clientes;


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
        return view('ClientesCatalogo.Table', compact('Titulo', 'Clientes'));
        
    }

}