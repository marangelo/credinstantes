<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProxVencer;
use App\Models\Zonas;

class ProxVencerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ProxVencer(){
        $Zonas       = Zonas::getZonas(); 
        $Titulo      = "PROX. A VENCER";
        return view('ProxVencer.Form', compact('Titulo','Zonas'));

    }
    public function getProxVencer(Request $request)
    {
        $response = ProxVencer::getProxVencer($request);
        
        return response()->json($response);
    }
}