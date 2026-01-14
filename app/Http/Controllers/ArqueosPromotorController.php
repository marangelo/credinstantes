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
}