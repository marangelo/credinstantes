<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Abono;
use App\Models\Zonas;


class DispensaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ViewDispensa(){
        $Zonas       = Zonas::getZonas(); 
        $Titulo      = "PROX. A VENCER";
        return view('Dispensa.Home', compact('Titulo','Zonas'));

    }


    public function getDispensa(Request $request)
    {
        $response = Abono::getDataReporteDispensa($request);
        
        return response()->json($response);
    }
}