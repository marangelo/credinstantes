<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Models\Usuario;
use App\Traits\CheckUserLock;

class MonitoreoActividadController extends Controller
{
    use CheckUserLock;

    /**
     * IDs de usuario (users.id) autorizados a ver el monitoreo de actividad.
     * Manejado por ID de forma explicita, segun lo solicitado.
     */
    const ADMINS_AUTORIZADOS = [7, 32];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $response = $this->checkUserLock();
            if ($response) {
                return $response;
            }
            if (!in_array(Auth::id(), self::ADMINS_AUTORIZADOS)) {
                return redirect('/')->with('error', 'Acceso no autorizado.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $Titulo = "Monitoreo de Actividad";
        // Solo usuarios de Operaciones (rol 3) para el filtro.
        $Usuarios = Usuario::where('activo', 'S')->where('id_rol', 3)->get();
        return view('MonitoreoActividad.Home', compact('Titulo', 'Usuarios'));
    }

    public function getData(Request $request)
    {
        return response()->json(ActivityLog::getData($request));
    }

    public function exportExcel(Request $request)
    {
        return ActivityLog::ExportExcel($request);
    }
}
