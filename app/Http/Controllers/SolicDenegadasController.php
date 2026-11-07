<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SolicDenegadas;
use App\Models\Usuario;
use App\Traits\CheckUserLock;

class SolicDenegadasController extends Controller
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

    public function SolicDenegadas()
    {
        $Titulo   = "Solic. Denegadas";
        $Usuarios = Usuario::with('RolName')->where('activo','S')->get();
        return view('SolicDenegadas.Home', compact('Titulo', 'Usuarios'));
    }

    public function getSolicDenegadas(Request $request)
    {
        $response = SolicDenegadas::getSolicDenegadas($request);
        return response()->json($response);
    }

    public function ExportSolicDenegadas(Request $request)
    {
        SolicDenegadas::ExportSolicDenegadas($request);
    }

    public function ReactivarSolicDenegada(Request $request)
    {
        $response = SolicDenegadas::ReactivarSolicDenegada($request);
        return response()->json($response);
    }
}
