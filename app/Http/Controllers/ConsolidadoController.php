<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Consolidado;


class ConsolidadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Consolidado()
    {
        $Titulo = "Consolidado";
       
        $Consolidado = Consolidado::CalcConsolidado(date('Y'));
        return view('Consolidado.Home', compact('Titulo', 'Consolidado'));
    }
}