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
    public function Init($Zona)
    {         
        $InitArqueo         = ArqueoPromotor::InitArqueo($Zona);
        return response()->json($InitArqueo);
    }
    public function getDataArqueosPromotor(Request $request)
    {
        $response = ArqueoPromotor::getData($request);
        
        return response()->json($response);
    }
    public function Detalles($ID)
    {         
        $Titulo     = "Arqueo Nuevo";
        $Arqueo     = ArqueoPromotor::where('id_arqueo_prom', $ID)->first();
        return view('ArqueosPromotor.Detalles', compact('Titulo','Arqueo'));
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

    public function RemoveArqueoPromotor(Request $request)
    {         
        $Arqueo     = $request->input('Arqueo');
        $resultado = ArqueoPromotor::where('id_arqueo_prom',$Arqueo)->update([
            "estado_arqueo" => 2
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Arqueo eliminado correctamente',
            'data'    => [
                'id' => $resultado
            ]
        ], 200);
    }

    public function ExportDetalles($ID)
    {
        $response = ArqueoPromotor::Export($ID);
    }
}