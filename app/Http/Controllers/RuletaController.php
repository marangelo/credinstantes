<?php

namespace App\Http\Controllers;

use App\Models\Ruleta;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// API para la app Ruleta de Premios (Next.js). Solo la llama el servidor de la ruleta,
// autenticado con la clave compartida X-Ruleta-Key. La sesión del promotor vive en la app.
class RuletaController extends Controller
{
    // 1 = Administrador, 4 = Promotor
    const ROLES_PERMITIDOS = [1, 2];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $apiKey = config('services.ruleta.key');
            if (empty($apiKey) || !hash_equals($apiKey, (string) $request->header('X-Ruleta-Key'))) {
                return response()->json(['message' => 'No autorizado'], 401);
            }
            return $next($request);
        });

        // Cada llamada (salvo el login) trae el id del promotor de la sesión de la ruleta. Si lo
        // bloquearon, lo desactivaron o le quitaron el rol en Credinstantes, se rechaza y la app
        // cierra su sesión: no alcanza con haberlo validado al ingresar.
        $this->middleware(function ($request, $next) {
            if (!self::UsuarioHabilitado((int) $request->input('id_usuario'))) {
                return response()->json([
                    'CODIGO'  => 'USUARIO_BLOQUEADO',
                    'MENSAJE' => 'Tu usuario ya no está habilitado para la ruleta.',
                ], 403);
            }
            return $next($request);
        })->except('Login');
    }

    // Mismo criterio que el login: activo, sin bloqueo y con un rol permitido
    private static function UsuarioHabilitado($IdUsuario)
    {
        return $IdUsuario > 0 && Usuario::where('id', $IdUsuario)
            ->where('activo', 'S')
            ->where('Lock', 1)
            ->whereIn('id_rol', self::ROLES_PERMITIDOS)
            ->exists();
    }

    public function Login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'clave'   => 'required|string',
        ]);

        $user = Usuario::where('email', $request->input('usuario'))
            ->where('activo', 'S')
            ->where('Lock', 1)
            ->whereIn('id_rol', self::ROLES_PERMITIDOS)
            ->first();

        if (!$user || !Hash::check($request->input('clave'), $user->password)) {
            return response()->json(['message' => 'Usuario o contraseña incorrectos'], 401);
        }

        return response()->json([
            'id'      => $user->id,
            'nombre'  => $user->nombre,
            'usuario' => $user->email,
            'rol'     => $user->id_rol,
            'zona'    => $user->id_zona,
        ]);
    }

    // La app lo llama en cada cambio de pantalla: el middleware del constructor ya revisó que el
    // promotor siga habilitado; si llega acá, la sesión sigue válida.
    public function Sesion()
    {
        return response()->json(['CODIGO' => 'OK']);
    }

    public function BuscarCliente(Request $request)
    {
        // Cédula y número de crédito activo juntos: el crédito tiene que ser de esa cédula
        $request->validate([
            'cedula'  => 'required|string|max:50',
            'credito' => 'required|integer|min:1',
        ]);

        list($Status, $Respuesta) = Ruleta::BuscarCliente($request->input('cedula'), (int) $request->input('credito'));

        return response()->json($Respuesta, $Status);
    }

    public function Preparar(Request $request)
    {
        $request->validate(['id_clientes' => 'required|integer']);

        list($Status, $Respuesta) = Ruleta::ClientePorId((int) $request->input('id_clientes'));

        return response()->json($Respuesta, $Status);
    }

    public function MisGiros(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|integer',
            'periodo'    => 'nullable|in:hoy,semana,mes',
        ]);

        list($Status, $Respuesta) = Ruleta::MisGiros((int) $request->input('id_usuario'), $request->input('periodo', 'hoy'));

        return response()->json($Respuesta, $Status);
    }

    public function Giro(Request $request)
    {
        $request->validate(['codigo' => 'required|string|max:20']); // 8 dígitos (o RP-XXXXXX en giros viejos)

        list($Status, $Respuesta) = Ruleta::GiroPorCodigo($request->input('codigo'));

        return response()->json($Respuesta, $Status);
    }

    // id_usuario viene de la sesión firmada de la app; la clave compartida garantiza que la llamada es del servidor de la ruleta
    public function Girar(Request $request)
    {
        $request->validate([
            'id_usuario'  => 'required|integer',
            'id_clientes' => 'required|integer',
            'id_creditos' => 'required|integer|min:1',
        ]);

        list($Status, $Respuesta) = Ruleta::Girar(
            (int) $request->input('id_usuario'),
            (int) $request->input('id_clientes'),
            (int) $request->input('id_creditos'),
            self::ROLES_PERMITIDOS
        );

        return response()->json($Respuesta, $Status);
    }
}
