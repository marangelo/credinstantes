<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProxVencer;
use App\Models\Zonas;

class FormatosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function PagareALaOrden(){
    
        //return view('Formatos.PagarALaOrden');
        $pdf = \PDF::loadView('Formatos.PagarALaOrden');
        return $pdf->download('PagarALaOrden.pdf');

    }
    public function SolicitudCredito(){
    
       // return view('Formatos.Solicitud_Credito');
        //return view('Formatos.Garantias');
        
        //$pdf = \PDF::loadView('Formatos.Solicitud_Credito');
        //return $pdf->download('FormatoCliente.pdf');

        $pdf = \PDF::loadView('Formatos.Garantias');
        return $pdf->download('FormatoGarantias.pdf');

    }
    public function Pagare(){
    
       // return view('Formatos.Pagare');

        $pdf = \PDF::loadView('Formatos.Pagare');
        return $pdf->download('FormatoPagare.pdf');
    }

}