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
    
    public function ViewClientesNA($IdZona)
    {         
        $Titulo     = "Catalogo de Clientes No Activos";

        $ClientesNA = ClientesNA::all()->pluck('id_cliente')->toArray();        
        $Zonas       = Zonas::getZonas(); 

        $Clientes   = Clientes::whereIn('id_clientes', $ClientesNA)
                            ->when($IdZona > -1, function ($query) use ($IdZona) {
                                $query->where('id_zona', $IdZona);
                            })->get();

        
        return view('ClientesNA.Table', compact('Titulo', 'Clientes', 'Zonas', 'IdZona'));
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