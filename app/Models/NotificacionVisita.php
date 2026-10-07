<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class NotificacionVisita extends Model
{
    protected $table = "tbl_notificaciones_visita";
    protected $primaryKey = 'id_notificacion';
    public $timestamps = false;

    public function Credito()
    {
        return $this->hasOne(Credito::class, 'id_creditos', 'id_creditos');
    }

    public function Cliente()
    {
        return $this->hasOne(Clientes::class, 'id_clientes', 'id_clientes');
    }

    public function Usuario()
    {
        return $this->hasOne(Usuario::class, 'id', 'id_usuario');
    }

    // Consulta base con cliente, zona y usuario que dejó la notificación
    private static function Consulta()
    {
        return NotificacionVisita::from('tbl_notificaciones_visita as n')
            ->join('tbl_clientes as c', 'c.id_clientes', '=', 'n.id_clientes')
            ->leftJoin('cat_zona as z', 'z.id_zona', '=', 'c.id_zona')
            ->leftJoin('users as u', 'u.id', '=', 'n.id_usuario')
            ->where('n.activo', 'S')
            ->select(
                'n.id_notificacion', 'n.id_creditos', 'n.id_clientes',
                'n.fecha_visita', 'n.hora_visita', 'n.monto_atrasado',
                'n.fecha_proxima', 'n.comentario', 'n.created_at',
                'c.nombre', 'c.apellidos', 'z.nombre_zona', 'u.nombre as usuario'
            )
            ->orderBy('n.id_notificacion', 'desc');
    }

    private static function Formato($rows)
    {
        $array = [];
        foreach ($rows as $r) {
            $array[] = [
                'Id'          => $r->id_notificacion,
                'IdCredito'   => $r->id_creditos,
                'IdCliente'   => $r->id_clientes,
                'Cliente'     => strtoupper($r->nombre . ' ' . $r->apellidos),
                'Zona'        => $r->nombre_zona ?? '-',
                'FechaVisita' => date('d/m/Y', strtotime($r->fecha_visita)),
                'HoraVisita'  => date('h:i A', strtotime($r->hora_visita)),
                'Monto'       => $r->monto_atrasado,
                'Proxima'     => is_null($r->fecha_proxima) ? '-' : date('d/m/Y', strtotime($r->fecha_proxima)),
                'Comentario'  => $r->comentario ?? '',
                'Usuario'     => strtoupper($r->usuario ?? '-'),
            ];
        }
        return $array;
    }

    public static function getNotificaciones(Request $request)
    {
        $Obj = self::Consulta()
            ->whereBetween('n.fecha_visita', [$request->input('dtIni'), $request->input('dtEnd')]);

        if ($request->filled('IdUser')) {
            $Obj->where('n.id_usuario', $request->input('IdUser'));
        }

        // Administrador, caja y supervisor ven todas las zonas; el resto solo la suya
        if (!in_array(Auth::User()->id_rol, [1, 3, 5])) {
            $Obj->where('c.id_zona', Auth::User()->id_zona);
        }

        return self::Formato($Obj->get());
    }

    public static function getNotificacionesCliente($IdCliente)
    {
        return self::Formato(self::Consulta()->where('n.id_clientes', $IdCliente)->get());
    }

    public static function SaveNotificacion(Request $request)
    {
        if ($request->ajax()) {
            try {
                $Credito = Credito::find($request->input('IdCredito'));

                if (!$Credito) {
                    return response()->json(['success' => false, 'message' => 'Credito no encontrado'], 404);
                }

                // Evita duplicados por doble click o reintentos: si el mismo usuario ya registró
                // esta misma notificación hace menos de 2 minutos, se devuelve la existente.
                $Existente = NotificacionVisita::where('id_creditos', $Credito->id_creditos)
                    ->where('id_usuario', Auth::id())
                    ->where('fecha_visita', $request->input('FechaVisita'))
                    ->where('hora_visita', $request->input('HoraVisita'))
                    ->where('monto_atrasado', $request->input('MontoAtrasado', 0))
                    ->where('activo', 'S')
                    // created_at lo llena MySQL, así que se compara con la hora de MySQL y no con la de PHP
                    ->whereRaw('created_at >= NOW() - INTERVAL 2 MINUTE')
                    ->orderBy('id_notificacion', 'desc')
                    ->first();

                if ($Existente) {
                    return response()->json(['success' => true, 'id' => $Existente->id_notificacion]);
                }

                $Notificacion = new NotificacionVisita();
                $Notificacion->id_creditos    = $Credito->id_creditos;
                $Notificacion->id_clientes    = $Credito->id_clientes;
                $Notificacion->fecha_visita   = $request->input('FechaVisita');
                $Notificacion->hora_visita    = $request->input('HoraVisita');
                $Notificacion->monto_atrasado = $request->input('MontoAtrasado', 0);
                $Notificacion->fecha_proxima  = $request->input('FechaProxima') ?: null;
                $Notificacion->comentario     = mb_substr((string) $request->input('Comentario'), 0, 100);
                $Notificacion->id_usuario     = Auth::id();
                $Notificacion->save();

                return response()->json(['success' => true, 'id' => $Notificacion->id_notificacion]);

            } catch (\Exception $e) {
                \Log::channel('log_general')->error("SaveNotificacion: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }
    }
}
