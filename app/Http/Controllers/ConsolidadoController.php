<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;


class ConsolidadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Consolidado()
    {
        $Titulo = "Consolidado";
        return view('Consolidado.Home', compact('Titulo'));
    }
}