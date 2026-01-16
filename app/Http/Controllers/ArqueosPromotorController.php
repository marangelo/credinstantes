<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ArqueoPromotor;
use App\Models\Usuario;

class ArqueosPromotorController extends Controller 
{  
    public function ShowHome()
    {         
        $Titulo      = "Arqueos";
        $Promotores = Usuario::where('id_rol', 4)->where('activo', 'S')->get();

        return view('ArqueosPromotor.Home', compact('Titulo', 'Promotores'));
    }
    public function getDataArqueosPromotor(Request $request)
    {
        $response = ArqueoPromotor::getData($request);
        
        return response()->json($response);
    }
    public function Detalles($ID)
    {         
        $Titulo     = "Arqueo Nuevo";
        return view('ArqueosPromotor.Detalles', compact('Titulo', 'ID'));
    }
    public function TableDetalles(Request $request)
    {
        $response = ArqueoPromotor::TableDetalles($request);
        
        return response()->json($response);
    }
    public function UpdateArqueoPromotor(Request $request)
    {
        $response = ArqueoPromotor::UpdateArqueoPromotor($request);
        
        return response()->json($response);
    }
    public function SaveArqueoPromotor(Request $request)
    {         
        $response = ArqueoPromotor::SaveArqueoPromotor($request);
        
        return response()->json($response);
    }
}