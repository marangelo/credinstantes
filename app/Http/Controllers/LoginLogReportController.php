<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\LoginLogReport;
use App\Models\Usuario;
use App\Traits\CheckUserLock;

class LoginLogReportController extends Controller
{
    use CheckUserLock;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $response = $this->checkUserLock();
            if ($response) {
                return $response;
            }
            if (!in_array(Session::get('rol'), [1])) {
                return redirect('/')->with('error', 'Acceso no autorizado.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $Titulo   = "Reporte de Accesos";
        $Usuarios = Usuario::with('RolName')->where('activo', 'S')->get();
        return view('LoginLogReport.Home', compact('Titulo', 'Usuarios'));
    }

    public function getData(Request $request)
    {
        $response = LoginLogReport::getData($request);
        return response()->json($response);
    }

    public function exportExcel(Request $request)
    {
        LoginLogReport::ExportExcel($request);
    }
}
