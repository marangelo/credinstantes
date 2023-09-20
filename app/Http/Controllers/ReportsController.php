<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Exports\ExportAbonos;
use App\Exports\ExportVisitar;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Clientes;
use App\Models\Municipios;
use App\Models\Departamentos;
use App\Models\DiasSemana;
use App\Models\Abono;
use App\Models\Credito;
use App\Models\Credinstante;
use App\Models\ReportsModels;
use CodersFree\Date\Date;

class ReportsController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function Visitar()
    {           
        $DiasSemana  = DiasSemana::getDiasSemana();  
        $Titulo         = "Visitar";
        return view('Reports.Visitar', compact('DiasSemana','Titulo'));
        
    }
    public function Abonos()
    {           
        $Clientes    = Clientes::getClientes();  
        $Titulo         = "Abonos";
        return view('Reports.Abonos', compact('Clientes','Titulo'));
        
    }
    public function Morosidad()
    {           
        $Clientes    = Clientes::getClientes();
        $Titulo         = "Morosidad";
        return view('Reports.Morosidad',compact('Clientes','Titulo'));
        
    }

    public function getVisitar(Request $request)
    {
        $response = ReportsModels::getVisitar($request);
        
        return response()->json($response);
    }
    public function getAbonos(Request $request)
    {
        $response = ReportsModels::getAbonos($request);
        
        return response()->json($response);
    }
    public function getMorosidad(Request $request)
    {
        $response = ReportsModels::getMorosidad($request);
        
        return response()->json($response);
    }

    public function exportAbonos(Request $request)
    {
        //return Excel::download(new ExportAbonos($request), 'Abonos.xlsx');
        $export = new ExportAbonos($request);
    
        $fileName = 'abonos.xlsx';
    
        return Excel::download($export, $fileName);
    }
    public function exportVisita(Request $request)
    {
        return Excel::download(new ExportVisitar($request), 'Visita.xlsx');
    }

}