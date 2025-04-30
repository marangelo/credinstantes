<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Auth;
use Session; 
use App\Models\Clientes;
use App\Models\Zonas;
use App\Models\ClientesNA;


class ControllerClientesNA extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function ViewClientesNA()
    {         
        $Titulo     = "Catalogo de Clientes No Activos";

        $ClientesNA = ClientesNA::all()->pluck('id_cliente')->toArray();
        $Clientes   = Clientes::whereIn('id_clientes', $ClientesNA)->get();
        return view('ClientesNA.Table', compact('Titulo', 'Clientes'));
    }
    public function ArchivarClient(Request $request)
    {         
        $response = ClientesNA::ArchivarClient($request);
        
        return response()->json($response);
    }
    public function UnArchivarClient(Request $request)
    {         
        $response = ClientesNA::UnArchivarClient($request);
        
        return response()->json($response);
    }
}