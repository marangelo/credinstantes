<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificacionVisita;
use App\Models\Usuario;
use App\Traits\CheckUserLock;

class NotificacionesController extends Controller
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
            return $next($request);
        });
    }

    public function index()
    {
        $Titulo   = "Notificaciones";
        $Usuarios = Usuario::with('RolName')->where('activo', 'S')->get();
        return view('Notificaciones.Home', compact('Titulo', 'Usuarios'));
    }

    public function getNotificaciones(Request $request)
    {
        return response()->json(NotificacionVisita::getNotificaciones($request));
    }

    public function getNotificacionesCliente($Id)
    {
        return response()->json(NotificacionVisita::getNotificacionesCliente($Id));
    }
}
