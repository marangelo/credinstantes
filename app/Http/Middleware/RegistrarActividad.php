<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Registra la actividad de los usuarios con rol de Operaciones (id_rol = 3)
 * en la tabla `activity_log`. Guarda una fila por cada request (vista,
 * impresion, exportacion o ajax) con la hora de entrada a la seccion.
 *
 * El insert es "fire and forget": cualquier error se traga para no romper
 * la aplicacion. Solo registra al rol 3, por lo que el volumen queda acotado.
 */
class RegistrarActividad
{
    /**
     * Rol vigilado. Solo se registran los movimientos de este rol.
     */
    const ROL_VIGILADO = 3;

    public function handle($request, Closure $next)
    {
        // Hora de entrada a la seccion (antes de procesar la peticion).
        $entrada = now();

        $response = $next($request);

        try {
            $user = Auth::user();

            if ($user && (int) $user->id_rol === self::ROL_VIGILADO) {
                DB::table('activity_log')->insert([
                    'user_id'        => $user->id,
                    'id_rol'         => $user->id_rol,
                    'nombre_usuario' => $user->nombre ?? ($user->name ?? null),
                    'metodo'         => $request->method(),
                    'ruta'           => mb_substr($request->path(), 0, 255),
                    'route_name'     => optional($request->route())->getName(),
                    'seccion'        => $this->resolverSeccion($request),
                    'tipo'           => $this->resolverTipo($request),
                    'registro_id'    => $this->resolverRegistroId($request),
                    'ip'             => $request->ip(),
                    'user_agent'     => mb_substr((string) $request->userAgent(), 0, 255),
                    'created_at'     => $entrada,
                ]);
            }
        } catch (\Throwable $e) {
            // Nunca interrumpir la aplicacion por el registro de auditoria.
            \Log::channel('log_general')->warning('RegistrarActividad: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Deriva un nombre de seccion legible a partir del controlador que
     * atendio la ruta (ej: "ClientesController@x" => "Clientes").
     * Si no hay controlador, usa el primer segmento de la URL.
     */
    private function resolverSeccion($request)
    {
        $accion = optional($request->route())->getActionName();

        if ($accion && strpos($accion, '@') !== false) {
            $clase = explode('@', $accion)[0];
            $corto = class_basename($clase);           // ClientesController
            $corto = preg_replace('/Controller$/', '', $corto); // Clientes
            if ($corto !== '') {
                return $corto;
            }
        }

        $segmento = explode('/', $request->path())[0];
        return $segmento !== '' ? $segmento : '/';
    }

    /**
     * Clasifica el tipo de accion: exportacion, impresion, vista o ajax.
     */
    private function resolverTipo($request)
    {
        $ruta   = strtolower($request->path());
        $nombre = strtolower((string) optional($request->route())->getName());
        $ref    = $ruta . ' ' . $nombre;

        // Exportaciones a Excel.
        if (strpos($ref, 'export') !== false) {
            return 'exportacion';
        }

        // Vistas de impresion / generacion de PDF.
        $patronesImpresion = ['voucher', 'print', 'pagare', 'solicitudcredito', 'creditoprint'];
        foreach ($patronesImpresion as $p) {
            if (strpos($ref, $p) !== false) {
                return 'impresion';
            }
        }

        if ($request->isMethod('get')) {
            return 'vista';
        }

        return 'ajax';
    }

    /**
     * Toma el primer parametro escalar de la ruta (normalmente el id del
     * registro consultado, editado o impreso).
     */
    private function resolverRegistroId($request)
    {
        $route = $request->route();
        if (!$route) {
            return null;
        }

        foreach ($route->parameters() as $valor) {
            if (is_scalar($valor)) {
                return mb_substr((string) $valor, 0, 100);
            }
        }

        return null;
    }
}
