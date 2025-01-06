<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Consolidado;
use App\Models\ConsolidadoCat;


class ConsolidadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Consolidado(Request $request)
    {
        $Titulo = "Consolidado";

        $year = $request->query('year');
    
        $Consolidado = Consolidado::CalcConsolidado($year);      
        
        
        return view('Consolidado.Home', compact('Titulo', 'Consolidado', 'year'));

        
    }
    public function AddConsolidado()
    {
        $Titulo = "Agregar Consolidado";
        $Indicadores = ConsolidadoCat::WhereIn('id_cat_consolidado',[8,9,12,13,14])->get();
        return view('Consolidado.add', compact('Titulo', 'Indicadores'));
    }
    public function getIndicadores(Request $request)
    {
        $response = Consolidado::getIndicadores($request);
        
        return response()->json($response);
    }
    public function SaveIndiador(Request $request)
    {
        $response = Consolidado::SaveIndiador($request);
        
        return response()->json($response);
    }
    public function RemoveIndicador(Request $request)
    {
        $response = Consolidado::RemoveIndicador($request);
        return response()->json($response);
    }
    public function getInfoIndicador(Request $request)
    {
        $response = Consolidado::getInfoIndicador($request);
        return response()->json($response);
    }

    public function ExportConsolidado(Request $request)
    {
        $response = Consolidado::ExportConsolidado($request);
    }
    
    public function UpdateConsolidado(Request $request)
    {
        $response = Consolidado::UpdateConsolidado($request);
        return response()->json($response);
    }

}