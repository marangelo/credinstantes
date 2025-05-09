<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CheckUserLock;
use Auth;
use App\Models\Crobrador;

class CobradorController extends Controller
{
    use CheckUserLock;
    public function __construct()
    {
        $this->middleware('auth');
        // Luego verificar si el usuario está bloqueado
        $this->middleware(function ($request, $next) {
            $response = $this->checkUserLock();
            if ($response) {
                return $response; 
            }
            return $next($request);
        });
    }

    public function ViewDesembolsados()
    {           
        $Titulo      = "Ingresos Diarios";
        return view('Cobrador.Desembolsos', compact('Titulo'));
        
    }
    public function getDesembolsados(Request $request)
    {
        $response = Crobrador::getDesembolsados($request);
        
        return $response;
    }
}
