<?php
namespace App\Http\Controllers;

use App\Models\ProxVencer;
use App\Models\Clientes;
use App\Models\Zonas;

class ProxVencerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ProxVencer(){
        $Clientes    = Clientes::getClientes(0);  
        $Zonas       = Zonas::getZonas(); 
        $Titulo      = "Ingresos Diarios";
        return view('ProxVencer.Form', compact('Clientes','Titulo','Zonas'));

    }
}