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
use App\Models\Estados;
use CodersFree\Date\Date;
use App\Traits\CheckUserLock;

class ReportsController extends Controller 
{
    use CheckUserLock; 
    public function __construct()
    {
        $this->middleware('auth');
        // Luego verificar si el usuario está bloqueado
        $this->middleware(function ($request, $next) {
            $response = $this->checkUserLock();
            if ($response) {
                return $response; 
            }
            return $next($request);
        });
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
    public function getDashboard($Opt,Request $request)
    {      
        $Fecha = $request->input('dt_ini');

        if ($Fecha >= date('Y-m-d')) {
            $response = ReportsModels::getDashboard($Opt, $Fecha);
        } else {
            $response = ReportsModels::getMetricasHistory($Opt, $Fecha);
        }

        //$response = ReportsModels::getDashboard($Opt, $Fecha);
        
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


    public function ViewHistorialPagos(Request $request)
    {
        $Titulo      = "Historial de Pagos";
        $Zonas       = Zonas::getZonas();  
        $Clientes    = Clientes::getClientes(0);  
        return view('HistorialPagos.Home',compact('Titulo','Zonas','Clientes'));
    }

    public function getHistorialPagos(Request $request)
    {
        $response = ReportsModels::getHistorialPagos($request);
        
        return response()->json($response);
    }

    public function getCreditos($IdCliente)
    {
        $Cliente = Clientes::find($IdCliente);
        $Credios_Cliente =  $Cliente->getCreditos;

        $dta[] = array(
            'Nombre'            => strtoupper($Cliente->nombre),
            'Apellidos'         => strtoupper($Cliente->apellidos),            
            "Estados_credito"   => Estados::all()->toArray(),
            'Creditos'          => $Credios_Cliente
        );

        return response()->json($dta);
    }
    public function CreditoPrint($IdCredito)
    {
        $Titulo     = "Historial de Pagos";
        $Credito    = Credito::find($IdCredito);
        $Pagos      = Abono::getHistorico($IdCredito);
        return view('HistorialPagos.Print',compact('Titulo','Credito','Pagos')); 
    }

    public function PrintViewPDF($IdCredito) 
    {
        $Titulo     = "Historial de Pagos";
        $Credito    = Credito::find($IdCredito);
        $Pagos      = Abono::getHistorico($IdCredito);

        $Nombre = strtoupper($Credito->Clientes->nombre . ' ' . $Credito->Clientes->apellidos). ' - ' . date('Y-m-d');

    
        $pdf = \PDF::loadView('HistorialPagos.PrintView', compact('Titulo','Credito','Pagos'));
        //return $pdf->stream('Historico de Pagos - ' . $Nombre . '.pdf');
        return $pdf->download('Historico de Pagos - ' . $Nombre . '.pdf');
    }

}