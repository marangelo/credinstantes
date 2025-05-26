<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clientes;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reactivacion(Request $request)
    {
        $DaysLastPayment = Clientes::getDaysLastPayment(269); 
        

        if($DaysLastPayment >= 10){
            dd('El cliente tiene más de 10 días es. Reactivacion', $DaysLastPayment);
        } else {
            dd('El cliente tiene menos de 10 días es. Renovación', $DaysLastPayment);
        }

    }
}
