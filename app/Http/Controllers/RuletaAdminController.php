<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Ruleta;
use App\Models\Zonas;
use App\Traits\CheckUserLock;

// Sección Ruleta de Premios dentro de Credinstantes (administración).
// El login de la app de la ruleta está aparte en RuletaController (API).
class RuletaAdminController extends Controller
{
    use CheckUserLock;

    // 1 = Administrador, 5 = Supervisor
    const ROLES_PERMITIDOS = [1, 5];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $response = $this->checkUserLock();
            if ($response) {
                return $response;
            }
            if (!in_array(Session::get('rol'), self::ROLES_PERMITIDOS)) {
                return redirect('/')->with('error', 'Acceso no autorizado.');
            }
            return $next($request);
        });
    }

    public function ShowHome()
    {
        $Titulo = "Ruleta";
        $Zonas  = Zonas::getZonas();
        return view('Ruleta.Home', compact('Titulo', 'Zonas'));
    }

    public function getGirosRuleta(Request $request)
    {
        return response()->json(Ruleta::getGiros($request));
    }

    public function ShowPromociones()
    {
        $Titulo = "Ruleta - Promociones";
        return view('Ruleta.Promociones', compact('Titulo'));
    }

    public function getPromocionesRuleta()
    {
        return response()->json(Ruleta::getPromociones());
    }

    public function RemovePromocionRuleta(Request $request)
    {
        return response()->json(Ruleta::RemovePromocion($request));
    }

    public function SavePromocionRuleta(Request $request)
    {
        return response()->json(Ruleta::SavePromocion($request));
    }
}
