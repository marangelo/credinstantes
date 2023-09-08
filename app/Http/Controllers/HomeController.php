<?php
namespace App\Http\Controllers;

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
        return view('Clientes.lista');
        
    }
    public function getUsuarios ()
    {          
        return view('Usuario.lista');
        
    }

}  