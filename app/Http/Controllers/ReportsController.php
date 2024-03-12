<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Exports\ExportAbonos;
use App\Exports\ExportVisitar;
use App\Exports\ExportPromotorMetricas;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Clientes;
use App\Models\Municipios;
use App\Models\Departamentos;
use App\Models\Zonas;
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
    public function RecuperacionCobro(){
        $Titulo      = "Recuperacion";
        return view('Reports.Recuperacion',compact('Titulo'));
    }
    public function Visitar()
    {           
        $DiasW       = DiasSemana::getDiasSemana();
        $Zonas       = Zonas::getZonas();  
        $Titulo      = "CLIENTES A VISITAR";
        return view('Reports.Visitar', compact('DiasW','Zonas','Titulo'));
        
    }
    public function Abonos()
    {           
        $Clientes    = Clientes::getClientes(0);  
        $Zonas       = Zonas::getZonas(); 
        $Titulo      = "Ingresos Diarios";
        return view('Reports.Abonos', compact('Clientes','Titulo','Zonas'));
        
    }
    public function Morosidad()
    {           
        $Clientes    = Clientes::getMorosos();
        $Zonas       = Zonas::getZonas(); 
        $Titulo      = "Morosidad";
        return view('Reports.Morosidad',compact('Clientes','Titulo','Zonas'));
        
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
    public function CalcRecuperacion(Request $request)
    {
        $response = ReportsModels::CalcRecuperacion($request);
        
        return response()->json($response);
    }
    public function getMorosidad(Request $request)
    {
        $response = ReportsModels::getMorosidad($request);
        
        return response()->json($response);
    }
    public function getDashboard($Opt)
    {
        $response = ReportsModels::getDashboard($Opt);
        
        return response()->json($response);
    }
    public function getDashboardPromotor($Opt)
    {
        $response = ReportsModels::getDashboardPromotor($Opt);
        
        return response()->json($response);
    }
    public function getMetricasPromotor($Opt)
    {
        $response = ReportsModels::getMetricasPromotor($Opt);
        
        return response()->json($response);
    }

    public function getClientesDesembolsados()
    {
        $response = ReportsModels::getClientesDesembolsados();
        
        return response()->json($response);
    }


    public function exportAbonos(Request $request)
    {
        return Excel::download(new ExportAbonos($request), 'Ingresos.xlsx');
    }
    public function exportVisita(Request $request)
    {
        return Excel::download(new ExportVisitar($request), 'Visita.xlsx');
    }

    public function ExportMetricasPromotor()
    {
        return Excel::download(new ExportPromotorMetricas, 'Promotor.xlsx');
    }

}