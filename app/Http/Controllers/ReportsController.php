<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

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
        return view('Reports.Visitar', compact('DiasSemana'));
        
    }
    public function Abonos()
    {           
        $Clientes    = Clientes::getClientes();  
        return view('Reports.Abonos', compact('Clientes'));
        
    }
    public function Morosidad()
    {           
        return view('Reports.Morosidad');
        
    }

    public function getVisitar(Request $request)
    {
        $response = ReportsModels::getVisitar($request);
        
        return response()->json($response);
    }

}