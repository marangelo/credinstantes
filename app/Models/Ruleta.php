<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

// Ruleta de Premios: promociones con límites globales de premios por día y por semana
// (lunes a domingo) y los giros que hacen los promotores desde la app de la ruleta.
class Ruleta
{
    // Premios de una promoción: dinero (monto) u objeto (nombre). PESO es la probabilidad
    // relativa: con todos en 1 cada premio tiene la misma chance.
    public static function PremiosDe($IdPromocion)
    {
        return DB::table('tbl_ruleta_premios')
            ->where('id_promocion', $IdPromocion)
            ->orderByRaw("tipo = 'OBJETO', monto, nombre")
            ->get()
            ->map(function ($p) {
                return [
                    'ID'     => $p->id_premio,
                    'TIPO'   => $p->tipo,
                    'MONTO'  => $p->tipo === 'DINERO' ? (float) $p->monto : null,
                    'NOMBRE' => $p->tipo === 'OBJETO' ? $p->nombre : null,
                    'PESO'   => (int) $p->peso,
                ];
            })
            ->values();
    }

    // Campos del premio de un giro: TIPO, MONTO (solo dinero) y PREMIO (nombre, solo objeto)
    private static function PremioDeGiro($g)
    {
        return [
            'TIPO'   => $g->tipo,
            'MONTO'  => $g->tipo === 'DINERO' ? (float) $g->monto : null,
            'PREMIO' => $g->tipo === 'OBJETO' ? $g->premio : null,
        ];
    }

    public static function getGiros(Request $request)
    {
        $dtIni = Carbon::parse($request->input('dtIni', date('Y-m-d')))->startOfDay();
        $dtEnd = Carbon::parse($request->input('dtEnd', date('Y-m-d')))->endOfDay();
        $Zona  = (int) $request->input('Zona', -1);

        $Giros = DB::table('tbl_ruleta_giros as g')
            ->join('tbl_clientes as c', 'c.id_clientes', '=', 'g.id_clientes')
            ->leftJoin('users as u', 'u.id', '=', 'g.id_usuario')
            ->whereBetween('g.fecha', [$dtIni, $dtEnd])
            ->when($Zona != -1, function ($q) use ($Zona) {
                return $q->where('g.id_zona', $Zona);
            })
            ->orderBy('g.fecha', 'desc')
            ->select(
                DB::raw("DATE_FORMAT(g.fecha, '%Y-%m-%d %H:%i') as FECHA"),
                'g.codigo as CODIGO',
                DB::raw("TRIM(CONCAT(c.nombre, ' ', IFNULL(c.apellidos, ''))) as CLIENTE"),
                'u.nombre as PROMOTOR',
                'g.tipo as TIPO',
                'g.monto as MONTO',
                'g.premio as PREMIO'
            )
            ->get();

        return [
            'DEMO'  => false,
            'GIROS' => $Giros,
        ];
    }

    public static function getPromociones()
    {
        $Hoy = Carbon::today();

        $Promociones = DB::table('tbl_ruleta_promociones as p')
            ->whereNull('p.deleted_at')
            ->orderBy('p.activa', 'desc')
            ->orderBy('p.fecha_inicio', 'desc')
            ->select(
                'p.*',
                DB::raw("(SELECT COUNT(*) FROM tbl_ruleta_giros g WHERE g.id_promocion = p.id_promocion AND g.fecha >= '{$Hoy->toDateString()}' AND g.fecha < '{$Hoy->copy()->addDay()->toDateString()}') as usados_hoy"),
                DB::raw("(SELECT COUNT(*) FROM tbl_ruleta_giros g WHERE g.id_promocion = p.id_promocion AND g.fecha >= '{$Hoy->copy()->startOfWeek(Carbon::MONDAY)->toDateString()}' AND g.fecha < '{$Hoy->copy()->startOfWeek(Carbon::MONDAY)->addWeek()->toDateString()}') as usados_semana")
            )
            ->get()
            ->map(function ($p) {
                return [
                    'ID'             => $p->id_promocion,
                    'NOMBRE'         => $p->nombre,
                    'FECHA_INICIO'   => $p->fecha_inicio,
                    'FECHA_FIN'      => $p->fecha_fin,
                    'PREMIOS'        => self::PremiosDe($p->id_promocion),
                    'LIMITE_DIARIO'  => (int) $p->limite_diario,
                    'LIMITE_SEMANAL' => (int) $p->limite_semanal,
                    'USADOS_HOY'     => (int) $p->usados_hoy,
                    'USADOS_SEMANA'  => (int) $p->usados_semana,
                    'ACTIVA'         => (bool) $p->activa,
                ];
            });

        return [
            'DEMO'        => false,
            'PROMOCIONES' => $Promociones,
        ];
    }

    public static function SavePromocion(Request $request)
    {
        $request->validate([
            'Id'            => 'nullable|integer',
            'Nombre'        => 'required|string|max:100',
            'FechaInicio'   => 'required|date',
            'FechaFin'      => 'required|date|after_or_equal:FechaInicio',
            'Premios'       => 'required|json',
            'LimiteDiario'  => 'required|integer|min:1',
            'LimiteSemanal' => 'required|integer|gte:LimiteDiario',
        ], [
            'Nombre.required'         => 'El nombre es obligatorio.',
            'Nombre.max'              => 'El nombre no puede pasar de 100 caracteres.',
            'FechaInicio.*'           => 'La fecha de inicio no es válida.',
            'FechaFin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'FechaFin.*'              => 'La fecha de fin no es válida.',
            'Premios.*'               => 'Agregá al menos un premio.',
            'LimiteDiario.*'          => 'El límite diario debe ser un número entero mayor a cero.',
            'LimiteSemanal.gte'       => 'El límite semanal no puede ser menor que el diario.',
            'LimiteSemanal.*'         => 'El límite semanal debe ser un número entero.',
        ]);

        $Id     = (int) $request->input('Id', 0);
        $Activa = $request->input('Activa') == 1;

        if ($Id > 0 && !DB::table('tbl_ruleta_promociones')->where('id_promocion', $Id)->whereNull('deleted_at')->exists()) {
            return ['GUARDADO' => false, 'MENSAJE' => 'La promoción ya no existe.'];
        }

        $Datos = [
            'nombre'         => trim($request->input('Nombre')),
            'fecha_inicio'   => $request->input('FechaInicio'),
            'fecha_fin'      => $request->input('FechaFin'),
            'limite_diario'  => (int) $request->input('LimiteDiario'),
            'limite_semanal' => (int) $request->input('LimiteSemanal'),
            'activa'         => $Activa ? 1 : 0,
            'id_usuario'     => Auth::id(),
            'updated_at'     => Carbon::now(),
        ];

        $Premios = self::ValidarPremios(json_decode($request->input('Premios'), true));

        $Desactivadas = 0;

        DB::transaction(function () use (&$Id, $Datos, $Premios, $Activa, &$Desactivadas) {
            // Solo puede haber una promoción activa: al activar esta se desactivan las demás
            if ($Activa) {
                $Desactivadas = DB::table('tbl_ruleta_promociones')
                    ->where('activa', 1)
                    ->where('id_promocion', '<>', $Id)
                    ->update(['activa' => 0, 'updated_at' => Carbon::now()]);
            }

            if ($Id > 0) {
                DB::table('tbl_ruleta_promociones')->where('id_promocion', $Id)->update($Datos);
            } else {
                $Id = DB::table('tbl_ruleta_promociones')->insertGetId($Datos + ['created_at' => Carbon::now()]);
            }

            // Los giros guardan una copia del premio (monto o nombre), así que reemplazar la lista
            // de premios no afecta el historial
            DB::table('tbl_ruleta_premios')->where('id_promocion', $Id)->delete();
            DB::table('tbl_ruleta_premios')->insert(array_map(function ($p) use ($Id) {
                return ['id_promocion' => $Id] + $p;
            }, $Premios));
        });

        return [
            'GUARDADO' => true,
            'MENSAJE'  => 'Promoción guardada.' . ($Desactivadas ? ' Se desactivó la promoción que estaba activa.' : ''),
        ];
    }

    // Borrado lógico: la promoción queda en la base con deleted_at y se desactiva. Sus giros se
    // conservan (Dashboard, historial y vouchers los siguen mostrando).
    public static function RemovePromocion(Request $request)
    {
        $Id = (int) $request->input('Id', 0);

        $Eliminada = DB::table('tbl_ruleta_promociones')
            ->where('id_promocion', $Id)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => Carbon::now(),
                'activa'     => 0,
                'id_usuario' => Auth::id(),
                'updated_at' => Carbon::now(),
            ]);

        return [
            'GUARDADO' => $Eliminada > 0,
            'MENSAJE'  => $Eliminada > 0 ? 'Promoción eliminada.' : 'La promoción ya no existe.',
        ];
    }

    // Premios del formulario de Promociones: [{tipo: DINERO|OBJETO, monto, nombre, peso}]
    private static function ValidarPremios($Lista)
    {
        $Error = function ($msg) { throw ValidationException::withMessages(['Premios' => [$msg]]); };

        if (!is_array($Lista) || count($Lista) === 0) {
            $Error('Agregá al menos un premio.');
        }

        $Premios = [];
        $Claves  = [];
        foreach ($Lista as $p) {
            $Tipo = isset($p['tipo']) ? $p['tipo'] : '';
            $Peso = isset($p['peso']) && $p['peso'] !== '' ? $p['peso'] : 1;

            if (!ctype_digit((string) $Peso) || $Peso < 1 || $Peso > 1000) {
                $Error('El peso de cada premio debe ser un número entero entre 1 y 1000.');
            }

            if ($Tipo === 'DINERO') {
                $Monto = isset($p['monto']) ? $p['monto'] : null;
                if (!is_numeric($Monto) || $Monto <= 0) {
                    $Error('Los premios en dinero deben tener un monto mayor a cero.');
                }
                $Clave = 'D:' . round((float) $Monto, 2);
                $Fila  = ['tipo' => 'DINERO', 'monto' => round((float) $Monto, 2), 'nombre' => null, 'peso' => (int) $Peso];
            } elseif ($Tipo === 'OBJETO') {
                $Nombre = trim(isset($p['nombre']) ? (string) $p['nombre'] : '');
                if ($Nombre === '' || mb_strlen($Nombre) > 60) {
                    $Error('Los objetos deben tener un nombre de hasta 60 caracteres.');
                }
                $Clave = 'O:' . mb_strtoupper($Nombre);
                $Fila  = ['tipo' => 'OBJETO', 'monto' => null, 'nombre' => $Nombre, 'peso' => (int) $Peso];
            } else {
                $Error('Cada premio debe ser dinero u objeto.');
            }

            if (isset($Claves[$Clave])) {
                $Error('Hay premios repetidos: cada monto u objeto debe aparecer una sola vez.');
            }
            $Claves[$Clave] = true;
            $Premios[] = $Fila;
        }

        return $Premios;
    }

    // Cédula sin guiones ni espacios y en mayúsculas: "001-150389-0012k" -> "0011503890012K"
    const CEDULA_SQL = "REPLACE(REPLACE(UPPER(TRIM(cedula)), '-', ''), ' ', '')";

    public static function NormalizarCedula($Cedula)
    {
        return preg_replace('/[^A-Z0-9]/', '', strtoupper((string) $Cedula));
    }

    public static function PromocionVigente($Lock = false)
    {
        $Hoy = Carbon::today()->toDateString();
        $q = DB::table('tbl_ruleta_promociones')
            ->whereNull('deleted_at')
            ->where('activa', 1)
            ->where('fecha_inicio', '<=', $Hoy)
            ->where('fecha_fin', '>=', $Hoy);
        return $Lock ? $q->lockForUpdate()->first() : $q->first();
    }

    // Giro previo del cliente en la promoción. Se busca por cédula y no por id, porque hay
    // clientes registrados dos veces con la misma cédula y no deben poder girar dos veces.
    public static function ParticipacionPrevia($IdPromocion, $Cedula)
    {
        $Ids = DB::table('tbl_clientes')
            ->whereRaw(self::CEDULA_SQL . ' = ?', [self::NormalizarCedula($Cedula)])
            ->pluck('id_clientes');

        $Previo = DB::table('tbl_ruleta_giros as g')
            ->leftJoin('users as u', 'u.id', '=', 'g.id_usuario')
            ->where('g.id_promocion', $IdPromocion)
            ->whereIn('g.id_clientes', $Ids)
            ->select('g.fecha', 'g.tipo', 'g.monto', 'g.premio', 'g.codigo', 'u.nombre as promotor')
            ->first();

        return $Previo ? [
            'FECHA'    => $Previo->fecha,
            'CODIGO'   => $Previo->codigo,
            'PROMOTOR' => $Previo->promotor,
        ] + self::PremioDeGiro($Previo) : null;
    }

    private static function QueryCliente()
    {
        return DB::table('tbl_clientes as c')
            ->leftJoin('cat_zona as z', 'z.id_zona', '=', 'c.id_zona')
            // activo es BIT(1): sin el CAST llega como "\0", que en PHP es true
            ->select('c.id_clientes', 'c.nombre', 'c.apellidos', 'c.cedula', 'c.telefono', DB::raw('CAST(c.activo AS UNSIGNED) as activo'), 'z.nombre_zona');
    }

    // estado_credito (tbl_estados): 1 Al Día, 2 En Mora, 3 Vencido, 4 Inactivo.
    // Un crédito "activo en este momento" es el que no fue anulado (activo = 1) y no está Inactivo.
    const ESTADOS_CREDITO_ACTIVO = [1, 2, 3];

    // Búsqueda para la app de la ruleta: se piden cédula y número de crédito (id_creditos) juntos,
    // como doble verificación. El cliente tiene varios créditos a lo largo del tiempo: solo se
    // acepta el que está activo hoy y que pertenece a esa cédula.
    public static function BuscarCliente($Cedula, $IdCredito)
    {
        $Credito = DB::table('tbl_creditos as cr')
            ->join('tbl_clientes as c', 'c.id_clientes', '=', 'cr.id_clientes')
            ->where('cr.id_creditos', $IdCredito)
            ->where('cr.activo', 1)
            ->select('cr.id_creditos', 'cr.id_clientes', 'cr.estado_credito', 'c.cedula')
            ->first();

        if (!$Credito) {
            return [404, ['CODIGO' => 'NO_ENCONTRADO', 'MENSAJE' => 'No encontramos ese número de crédito.']];
        }

        // Se compara normalizada: "001-150389-0012k" y "0011503890012K" son la misma cédula
        if (self::NormalizarCedula($Credito->cedula) !== self::NormalizarCedula($Cedula)) {
            return [404, ['CODIGO' => 'NO_COINCIDE', 'MENSAJE' => 'Ese número de crédito no pertenece a la cédula ingresada.']];
        }

        if (!in_array((int) $Credito->estado_credito, self::ESTADOS_CREDITO_ACTIVO, true)) {
            return [404, [
                'CODIGO'  => 'CREDITO_INACTIVO',
                'MENSAJE' => 'Ese crédito ya no está activo. Pedile al cliente el número de su crédito vigente.',
            ]];
        }

        return self::FichaCliente(self::QueryCliente()->where('c.id_clientes', $Credito->id_clientes)->first(), $Credito);
    }

    // Lo que necesita la pantalla de la ruleta: cliente, premios de la promoción vigente y si ya participó.
    public static function ClientePorId($IdCliente)
    {
        return self::FichaCliente(self::QueryCliente()->where('c.id_clientes', $IdCliente)->first());
    }

    // $Credito: el crédito activo con el que se identificó al cliente (solo en la búsqueda).
    private static function FichaCliente($Cliente, $Credito = null)
    {
        if (!$Cliente) {
            return [404, ['CODIGO' => 'NO_ENCONTRADO', 'MENSAJE' => 'No encontramos al cliente.']];
        }

        $Promocion = self::PromocionVigente();
        $Premios   = $Promocion ? self::PremiosDe($Promocion->id_promocion) : [];

        return [200, [
            'CODIGO'  => 'OK',
            'CLIENTE' => [
                'ID'       => $Cliente->id_clientes,
                'NOMBRE'   => trim($Cliente->nombre . ' ' . $Cliente->apellidos),
                'CEDULA'   => $Cliente->cedula,
                'TELEFONO' => $Cliente->telefono,
                'ZONA'     => $Cliente->nombre_zona,
                'ACTIVO'   => (bool) $Cliente->activo,
            ],
            'CREDITO'      => $Credito ? ['ID' => (int) $Credito->id_creditos, 'ESTADO' => (int) $Credito->estado_credito] : null,
            'PROMOCION'    => $Promocion ? ['ID' => $Promocion->id_promocion, 'NOMBRE' => $Promocion->nombre, 'PREMIOS' => $Premios] : null,
            'PARTICIPACION'=> $Promocion ? self::ParticipacionPrevia($Promocion->id_promocion, $Cliente->cedula) : null,
        ]];
    }

    // Giros de un promotor en un período (hoy | semana | mes), con resumen y la promoción vigente.
    // Lo usan Inicio e Historial de la app de la ruleta.
    public static function MisGiros($IdUsuario, $Periodo)
    {
        $Hoy = Carbon::today();
        switch ($Periodo) {
            case 'semana': $Desde = $Hoy->copy()->startOfWeek(Carbon::MONDAY); break;
            case 'mes':    $Desde = $Hoy->copy()->startOfMonth(); break;
            default:       $Desde = $Hoy->copy(); break;
        }
        $Hasta = $Hoy->copy()->addDay();

        $Giros = DB::table('tbl_ruleta_giros as g')
            ->join('tbl_clientes as c', 'c.id_clientes', '=', 'g.id_clientes')
            ->leftJoin('cat_zona as z', 'z.id_zona', '=', 'c.id_zona')
            ->where('g.id_usuario', $IdUsuario)
            ->where('g.fecha', '>=', $Desde)
            ->where('g.fecha', '<', $Hasta)
            ->orderBy('g.fecha', 'desc')
            ->select('g.codigo', 'g.tipo', 'g.monto', 'g.premio', 'g.fecha', 'z.nombre_zona as zona', DB::raw("TRIM(CONCAT(c.nombre, ' ', IFNULL(c.apellidos, ''))) as cliente"))
            ->get();

        $Dinero    = $Giros->where('tipo', 'DINERO');
        $Promocion = self::PromocionVigente();
        $Premios   = $Promocion ? self::PremiosDe($Promocion->id_promocion) : collect();
        $Montos    = $Premios->where('TIPO', 'DINERO')->pluck('MONTO');

        return [200, [
            'CODIGO'  => 'OK',
            'RESUMEN' => [
                // Solo dinero: los objetos se cuentan como giros pero no suman al entregado
                'GIROS'     => $Giros->count(),
                'ENTREGADO' => (float) $Dinero->sum('monto'),
                'MAYOR'     => (float) ($Dinero->max('monto') ?? 0),
                'OBJETOS'   => $Giros->count() - $Dinero->count(),
            ],
            'GIROS' => $Giros->map(function ($g) {
                return ['CODIGO' => $g->codigo, 'FECHA' => $g->fecha, 'CLIENTE' => $g->cliente, 'ZONA' => $g->zona ? trim($g->zona) : null] + self::PremioDeGiro($g);
            })->values(),
            'PROMOCION' => $Promocion ? [
                'NOMBRE'  => $Promocion->nombre,
                'VENCE'   => $Promocion->fecha_fin,
                'MIN'     => $Montos->isEmpty() ? null : (float) $Montos->min(),
                'MAX'     => $Montos->isEmpty() ? null : (float) $Montos->max(),
                'OBJETOS' => $Premios->where('TIPO', 'OBJETO')->pluck('NOMBRE')->values(),
            ] : null,
        ]];
    }

    // Detalle de un giro por su código, para la pantalla del premio y el voucher.
    public static function GiroPorCodigo($Codigo)
    {
        $Giro = DB::table('tbl_ruleta_giros as g')
            ->join('tbl_clientes as c', 'c.id_clientes', '=', 'g.id_clientes')
            ->join('tbl_ruleta_promociones as p', 'p.id_promocion', '=', 'g.id_promocion')
            ->leftJoin('users as u', 'u.id', '=', 'g.id_usuario')
            ->where('g.codigo', strtoupper(trim((string) $Codigo)))
            ->select('g.codigo', 'g.tipo', 'g.monto', 'g.premio', 'g.fecha', 'g.id_creditos', 'c.nombre', 'c.apellidos', 'c.cedula', 'u.nombre as promotor', 'p.nombre as promocion')
            ->first();

        if (!$Giro) {
            return [404, ['CODIGO' => 'NO_ENCONTRADO', 'MENSAJE' => 'No existe un giro con ese código.']];
        }

        return [200, [
            'CODIGO' => 'OK',
            'GIRO'   => [
                'CODIGO'    => $Giro->codigo,
                'FECHA'     => $Giro->fecha,
                'CLIENTE'   => trim($Giro->nombre . ' ' . $Giro->apellidos),
                'CEDULA'    => $Giro->cedula,
                'CREDITO'   => $Giro->id_creditos ? (int) $Giro->id_creditos : null, // null en giros anteriores al cambio
                'PROMOTOR'  => $Giro->promotor,
                'PROMOCION' => $Giro->promocion,
            ] + self::PremioDeGiro($Giro),
        ]];
    }

    // Giro desde la app de la ruleta. El premio lo decide el servidor, nunca la app.
    // Devuelve [codigo HTTP, respuesta]. Los rechazos de negocio van con 409 y un CODIGO.
    // $IdCredito: el crédito activo con el que el promotor identificó al cliente (cédula + crédito).
    public static function Girar($IdUsuario, $IdCliente, $IdCredito, array $RolesPermitidos)
    {
        $Promotor = DB::table('users')
            ->where('id', $IdUsuario)
            ->where('activo', 'S')
            ->where('Lock', 1)
            ->whereIn('id_rol', $RolesPermitidos)
            ->first();

        if (!$Promotor) {
            return [403, ['CODIGO' => 'USUARIO_INVALIDO', 'MENSAJE' => 'Tu usuario no está habilitado para girar.']];
        }

        $Cliente = DB::table('tbl_clientes')
            ->where('id_clientes', $IdCliente)
            ->where('activo', 1)
            ->first();

        if (!$Cliente) {
            return [404, ['CODIGO' => 'CLIENTE_INVALIDO', 'MENSAJE' => 'El cliente no existe o no está activo.']];
        }

        // Mismo criterio que la búsqueda: el crédito es del cliente y sigue activo en este momento
        $Credito = DB::table('tbl_creditos')
            ->where('id_creditos', $IdCredito)
            ->where('id_clientes', $Cliente->id_clientes)
            ->where('activo', 1)
            ->whereIn('estado_credito', self::ESTADOS_CREDITO_ACTIVO)
            ->first();

        if (!$Credito) {
            return [409, ['CODIGO' => 'CREDITO_INVALIDO', 'MENSAJE' => 'El crédito no es de este cliente o ya no está activo. Buscalo de nuevo.']];
        }

        return DB::transaction(function () use ($Promotor, $Cliente, $Credito) {
            $Ahora = Carbon::now();
            $Hoy   = $Ahora->copy()->startOfDay();

            // FOR UPDATE: los giros simultáneos esperan en fila, así el conteo de límites es exacto
            $Promocion = self::PromocionVigente(true);

            if (!$Promocion) {
                return [409, ['CODIGO' => 'SIN_PROMOCION', 'MENSAJE' => 'No hay una promoción activa en este momento.']];
            }

            $Previo = self::ParticipacionPrevia($Promocion->id_promocion, $Cliente->cedula);

            if ($Previo) {
                return [409, [
                    'CODIGO'  => 'YA_PARTICIPO',
                    'MENSAJE' => 'Este cliente ya participó en la promoción.',
                    'GIRO'    => $Previo,
                ]];
            }

            $UsadosHoy = DB::table('tbl_ruleta_giros')
                ->where('id_promocion', $Promocion->id_promocion)
                ->where('fecha', '>=', $Hoy)
                ->where('fecha', '<', $Hoy->copy()->addDay())
                ->count();

            if ($UsadosHoy >= $Promocion->limite_diario) {
                return [409, ['CODIGO' => 'AGOTADO_DIA', 'MENSAJE' => 'Se agotaron los premios de hoy. Intentá mañana.']];
            }

            $UsadosSemana = DB::table('tbl_ruleta_giros')
                ->where('id_promocion', $Promocion->id_promocion)
                ->where('fecha', '>=', $Hoy->copy()->startOfWeek(Carbon::MONDAY))
                ->where('fecha', '<', $Hoy->copy()->startOfWeek(Carbon::MONDAY)->addWeek())
                ->count();

            if ($UsadosSemana >= $Promocion->limite_semanal) {
                return [409, ['CODIGO' => 'AGOTADO_SEMANA', 'MENSAJE' => 'Se agotaron los premios de esta semana. Intentá el lunes.']];
            }

            $Premios = self::PremiosDe($Promocion->id_promocion);

            if ($Premios->isEmpty()) {
                return [409, ['CODIGO' => 'SIN_PREMIOS', 'MENSAJE' => 'La promoción no tiene premios configurados.']];
            }

            // Sorteo por peso: cada premio tiene PESO / suma de pesos de probabilidad.
            // La rueda de la app dibuja todas las rebanadas iguales: el peso no cambia el dibujo.
            $Suerte = random_int(1, $Premios->sum('PESO'));
            $Premio = $Premios->first(function ($p) use (&$Suerte) {
                $Suerte -= $p['PESO'];
                return $Suerte <= 0;
            });

            // Código del voucher: número entero de 8 dígitos al azar (no correlativo, para que no se adivine)
            do {
                $Codigo = (string) random_int(10000000, 99999999);
            } while (DB::table('tbl_ruleta_giros')->where('codigo', $Codigo)->exists());

            DB::table('tbl_ruleta_giros')->insert([
                'id_promocion' => $Promocion->id_promocion,
                'id_clientes'  => $Cliente->id_clientes,
                'id_creditos'  => $Credito->id_creditos,
                'id_usuario'   => $Promotor->id,
                'id_premio'    => $Premio['ID'],
                'id_zona'      => $Cliente->id_zona,
                'tipo'         => $Premio['TIPO'],
                'monto'        => $Premio['MONTO'],
                'premio'       => $Premio['NOMBRE'],
                'codigo'       => $Codigo,
                'fecha'        => $Ahora,
                'created_at'   => $Ahora,
                'updated_at'   => $Ahora,
            ]);

            return [200, [
                'CODIGO'  => 'OK',
                'GIRO'    => [
                    'FECHA'    => $Ahora->format('Y-m-d H:i:s'),
                    'ID_PREMIO'=> $Premio['ID'],
                    'TIPO'     => $Premio['TIPO'],
                    'MONTO'    => $Premio['MONTO'],
                    'PREMIO'   => $Premio['NOMBRE'],
                    'CODIGO'   => $Codigo,
                    'CLIENTE'  => trim($Cliente->nombre . ' ' . $Cliente->apellidos),
                    'PROMOTOR' => $Promotor->nombre,
                ],
                'PROMOCION' => [
                    'NOMBRE'            => $Promocion->nombre,
                    'PREMIOS'           => $Premios,
                    'RESTANTES_HOY'     => (int) $Promocion->limite_diario - $UsadosHoy - 1,
                    'RESTANTES_SEMANA'  => (int) $Promocion->limite_semanal - $UsadosSemana - 1,
                ],
            ]];
        });
    }
}
