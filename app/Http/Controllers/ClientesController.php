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
        $DaysLastPayment = Clientes::getDaysLastPayment(34);   

        dd($DaysLastPayment);
    }
}
