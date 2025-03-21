<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProxVencer;
use App\Models\Zonas;
use App\Models\Credito;

class FormatosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function PagareALaOrden(Request $request){
        $ID = $request->input('id');
        $Credito = Credito::find($ID);
        
        $pdf = \PDF::loadView('Formatos.PagarALaOrden', compact('Credito'));
        return $pdf->download('PagarALaOrden.pdf');
        //return view('Formatos.PagarALaOrden', compact('Credito'));

    }
    public function SolicitudCredito(Request $request){

        $ID = $request->input('id');

        $Credito = Credito::find($ID);
    
        $pdf = \PDF::loadView('Formatos.Solicitud_Credito', compact('Credito'));
        return $pdf->download('SolicitudCredito.pdf');
        //return view('Formatos.Solicitud_Credito', compact('Credito'));

    }
    public function Pagare(Request $request){
        $Credito = Credito::find($ID);

        return view('Formatos.Pagare', compact('Credito'));

        // $pdf = \PDF::loadView('Formatos.Pagare');
        // return $pdf->download('FormatoPagare.pdf');

        
    }

}