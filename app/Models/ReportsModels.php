<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Auth;
use CodersFree\Date\Date;

class ReportsModels extends Model {

    public static function getVisitar(Request $request)
    {
        $Date   = $request->input('Fecha_').' 00:00:00';

        $array_merge = [];

        //$DiaW_   = $request->input('DiaW_');
        $Zona_   = $request->input('Zona_');

        $Day    = date('N', strtotime($Date));

        $Creditos_Activos =  Credito::where('activo', 1)->where('estado_credito', 1);

        if ($Day > 0) {
            $Creditos_Activos->Where('id_diassemana',$Day);
        }
        if ($Zona_ > 0) {
            $Creditos_Activos->Where(function($query) use ($Zona_) {
                $query->whereHas('Clientes', function ($query) use ($Zona_) {
                    $query->where('id_zona', $Zona_);
                });
            });
        }
        $Creditos_ALDIA = $Creditos_Activos->get();
        $array_creditos_aldia = array();
        foreach ($Creditos_ALDIA as $key => $v) {

            if ($v->saldo > 0) {
                if ($v->Clientes->activo > 0) {
                    $array_creditos_aldia[$key] = [
                        "id_pagoabono"           => $v->id_creditos,
                        "Nombre"                 => $v->Clientes->nombre. ' ' . $v->Clientes->apellidos,
                        "direccion_domicilio"    => $v->Clientes->direccion_domicilio,
                        "zona"                   => (isset($v->Clientes->getZona->nombre_zona) && $v->Clientes->getZona->nombre_zona) ? $v->Clientes->getZona->nombre_zona : 'N/D' ,
                        "telefono"               => $v->Clientes->telefono,
                        "cuota"                  => $v->cuota,
                        "saldo"                  => $v->saldo,
                        "pendiente"              => $v->AbonoLogs->isNotEmpty() ? $v->AbonoLogs->first()->SALDO_PENDIENTE : 0,
                        "Estado"                 => strtoupper($v->Estado->nombre_estado)
                    ];
                }
                
            }
        }

        $Creditos_mora_vencidos =  Credito::where('activo', 1)->whereIn('estado_credito', [2,3]);
        if ($Zona_ > 0) {
            $Creditos_mora_vencidos->Where(function($query) use ($Zona_) {
                $query->whereHas('Clientes', function ($query) use ($Zona_) {
                    $query->where('id_zona', $Zona_);
                });
            });
        }
        $Creditos_Anexados = $Creditos_mora_vencidos->get();
        $array_anexados = array();
        foreach ($Creditos_Anexados as $key => $v) {

            if ($v->saldo > 0) {
                if ($v->Clientes->activo > 0) {
                    $array_anexados[$key] = [                    
                        "id_pagoabono"           => $v->id_creditos,
                        "Nombre"                 => $v->Clientes->nombre. ' ' . $v->Clientes->apellidos,
                        "direccion_domicilio"    => $v->Clientes->direccion_domicilio,
                        "zona"                   => (isset($v->Clientes->getZona->nombre_zona) && $v->Clientes->getZona->nombre_zona) ? $v->Clientes->getZona->nombre_zona : 'N/D' ,
                        "telefono"               => $v->Clientes->telefono,
                        "cuota"                  => $v->cuota,
                        "saldo"                  => $v->saldo,
                        "pendiente"              => $v->AbonoLogs->isNotEmpty() ? $v->AbonoLogs->first()->SALDO_PENDIENTE : 0,
                        "Estado"                 => strtoupper($v->Estado->nombre_estado)
                    ];
                }
                
            }
        }

        $array_merge = array_merge($array_creditos_aldia,$array_anexados);
        return $array_merge;
    }
    public static function getAbonos(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';
        $IdCln    = $request->input('IdCln');
        $IdZna    = $request->input('IdZna');

        $Obj =  Pagos::whereBetween('FECHA_ABONO', [$dtIni, $dtEnd])->Where('activo',1);
    
        if ($IdCln > 0) {
            $Obj->Where('id_clientes',$IdCln);
        }
        if ($IdZna > 0) {
            $Obj->Where('id_zona',$IdZna);
        }

        $Abonos = $Obj->get();

        $array_abonos = array();
        
        foreach ($Abonos as $key => $a) {

            $CAPITAL  = (strtotime($a->FECHA_ABONO) <= strtotime("2024-03-16")) ? $a->CAPITAL : $a->CAPITAL;

            $Ingreso_neto = $CAPITAL + $a->INTERES ;            
            
            $array_abonos[$key] = [
                "id_abonoscreditos" => $a->id_abonoscreditos,
                "fecha_cuota"       => $a->FECHA_ABONO,
                "Nombre"            => strtoupper($a->credito->Clientes->nombre),
                "apellido"          => strtoupper($a->credito->Clientes->apellidos),
                "cuota_cobrada"     => $Ingreso_neto,
                "pago_capital"      => $CAPITAL,
                "pago_intereses"    => $a->INTERES,
            ];
                
        }


        return $array_abonos;
    }

    public static function CalcRecuperacion(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 00:00:00';
        
        $Cobra    = Auth::id();
        $id_zona  = Auth::User()->id_zona;
        
        $pago_capital = 0 ;

        $user_home = Usuario::where('id_rol', 1)->pluck('id')->first();
        $Obj =  Pagos::whereBetween('FECHA_ABONO', [$dtIni, $dtEnd])->whereIn('registrado_por', [$Cobra,$user_home])->Where('id_zona',$id_zona);   

        
        $pago_intereses = $Obj->sum( 'INTERES' );       
        
        foreach ($Obj->get() as $k => $p) {
            $pago_capital  += (strtotime($p->FECHA_ABONO) <= strtotime("2024-03-16")) ? $p->CAPITAL : $p->CAPITAL;
        }
        
        $Total_Recuperado = $pago_capital + $pago_intereses;

        return $Total_Recuperado;
    }
    public static function getMorosidad(Request $request)
    {
        $IdZna    = $request->input('IdZna');
        
        $Clientes = Clientes::getClientes(0);

        $Clientes = ($IdZna > 0) ? $Clientes->Where('id_zona',$IdZna) : $Clientes ;


        $span = '';
        $Color = '';
        $isAdded =  false;
        $IdCreditoVencido = 0 ;
        
        $array_clientes = [];
        foreach ($Clientes as $key => $c) {


            if ($c->getCreditos->isNotEmpty()) {

                switch ($c->getCreditos->first()->estado_credito) {
                    case 1:
                        $Color = 'bg-success';
                        $isAdded =  false;
                        break;
                    case 2:
                        $Color = 'bg-danger';
                        $isAdded =  true;
                        break;
                    case 3:
                        $Color = 'bg-warning';
                        $isAdded =  true;
                        break;
                    case 4:
                        $Color = '';
                        $isAdded =  false;
                        break;
                    
                    default:
                        $Color = '';
                        break;
                }
                
                $span = '<span class="badge '.$Color.'"> '.$c->getCreditos->first()->Estado->nombre_estado.'</span>';
            } else {
                $span = '-';
            }

            if ($isAdded) {

                $cuota = $c->getCreditos->isNotEmpty() ? $c->getCreditos->first()->cuota : 0;
                $saldo = $c->getCreditos->isNotEmpty() ? $c->getCreditos->first()->saldo : 0;
                //$pendi = $c->getCreditos->isNotEmpty() ? $c->getCreditos->first()->AbonoLogs->first()->SALDO_PENDIENTE : 0;

                if ($c->getCreditos->isNotEmpty()) {
                    $pendi =  $saldo;
                    if(isset($c->getCreditos->first()->AbonoLogs->first()->SALDO_PENDIENTE)) {
                        $pendi =$c->getCreditos->first()->AbonoLogs->first()->SALDO_PENDIENTE;
                   }
                } else {
                    $pendi = 0;
                }
                

           
                
                $array_clientes[] = [
                    "IdCliente"     => $c->id_clientes,
                    "nombre"        => $c->nombre,
                    "apellidos"     => $c->apellidos,
                    "Direccion"     => $c->direccion_domicilio,
                    "telefono"      => $c->telefono,
                    "cuota"         => number_format($cuota,2),
                    "saldo"         => number_format($saldo,2),
                    "pendiente"     => number_format($pendi,2),
                    "Estado"        => $span,
                    "IdCredito"     => $IdCreditoVencido,
                    "Zona"          => (isset($c->getZona->nombre_zona) && $c->getZona->nombre_zona) ? $c->getZona->nombre_zona : 'N/D'  ,

                ];
            }
            
        
        }

        return $array_clientes;
    }

    public static function getDashboard($Opt)
    {
        $array_dashboard    = [];
        $vLabel             = [];
        $vData              = [];
        $ttPagoCapital      = 0;
        $ttPagoIntereses    = 0;

        $dtNow  = date('Y-m-d');
        $D1     = date('Y-m-01', strtotime($dtNow)). ' 00:00:00';
        $D2     = date('Y-m-t', strtotime($dtNow)). ' 23:59:59';    
        $role   = Auth::User()->id_rol;


        $MoraAtrasada = PagosFechas::getMora($Opt,'atrasada');
        $MoraVencida  = PagosFechas::getMora($Opt,'vencida');

        if ($role == 2) {
            $Opt = Auth::User()->id_zona;
        }
    
        $Dias = Pagos::selectRaw('DAY(FECHA_ABONO) as dy, SUM((CASE WHEN FECHA_ABONO <= "2024-03-16" THEN CAPITAL ELSE CAPITAL END) + INTERES) as total, 
                                    SUM((CASE WHEN FECHA_ABONO <= "2024-03-16" THEN CAPITAL ELSE CAPITAL END)) CAPITAL, SUM(INTERES) INTERES')
                ->whereBetween('FECHA_ABONO', [$D1, $D2])
                ->where('activo', 1)
                ->when($Opt > -1, function ($query) use ($Opt) {
                    $query->where('id_zona', $Opt);
                })
                ->groupByRaw('DAY(FECHA_ABONO)')
                ->get();

        
        $Saldos_Cartera = Credito::Saldos_Cartera($Opt);
        $Clientes       = Credito::Creditos($Opt);



        foreach ($Dias as $dia) {
            $vLabel[]   = 'D' . $dia->dy; 
            $vData[]    = $dia->total; 
            $ttPagoCapital      += $dia->CAPITAL; 
            $ttPagoIntereses    += $dia->INTERES;
        }
        $ttCuotaCobrada     = $ttPagoCapital + $ttPagoIntereses;
        
        $array_dashboard = [
            "INGRESO"           => $ttCuotaCobrada,
            "CAPITAL"           => $ttPagoCapital,
            "INTERESES"         => $ttPagoIntereses,
            "SALDOS_CARTERA"    => $Saldos_Cartera,
            "MORA_ATRASADA"     => $MoraAtrasada,
            "MORA_VENCIDA"      => $MoraVencida,
            "clientes_activos"  => $Clientes->count(),
            "label"             => $vLabel,
            "Data"              => $vData
        ];

        return $array_dashboard;
    }

    public static function getDashboardPromotor($Zona)
    {
        $array_dashboard    = [];
        $vLabel             = [];
        $vData              = [];
        $ttPagoCapital      = 0;
        $ttPagoIntereses    = 0;

        $dtNow  = date('Y-m-d');
        $D1     = date('Y-m-01', strtotime($dtNow)). ' 00:00:00';
        $D2     = date('Y-m-t', strtotime($dtNow)). ' 23:59:59';    

        $role   = Auth::User()->id_rol;
        $Prom   = Auth::id();

        $Creditos   = Credito::where('asignado',$Prom)->whereBetween('fecha_apertura', [$D1, $D2]);
        $Represtamo = Reloan::where('user_created',$Prom)->whereBetween('date_reloan', [$D1, $D2]);
        
        if ($Zona > 0) {            
            $Creditos->Where(function($query) use ($Zona) {
                $query->whereHas('Clientes', function ($query) use ($Zona) {
                    $query->where('id_zona', $Zona);
                });
            });

            $Represtamo->Where(function($query) use ($Zona) {
                $query->whereHas('Clientes', function ($query) use ($Zona) {
                    $query->where('id_zona', $Zona);
                });
            });
        }
        
        
        $Clientes_Nuevo     = $Creditos->count();
        $Reloan_count       = $Represtamo->count();

        $Clientes_Nuevo     = $Clientes_Nuevo - $Reloan_count;
        $Clientes_Nuevo     = ($Clientes_Nuevo < 0 ) ? 0 : $Clientes_Nuevo ;

        
        $RePrestamo         = $Represtamo->sum('amount_reloan');

        $SALDOS_COLOCADOS   = $Creditos->sum('monto_credito') ;
        
        
        $array_dashboard = [
            "CLIENTES_NUEVO"        => $Clientes_Nuevo,
            "RE_PRESTAMOS"          => $Reloan_count,
            "SALDOS_COLOCADOS"      => $SALDOS_COLOCADOS,
            "LISTA_CLIENTES"        => Clientes::Clientes_promotor($Zona)
        ];

        return $array_dashboard;
    }
    public static function getMetricasPromotor($Prom)
    {
        $dtNow  = date('Y-m-d');
        $D1     = date('Y-m-01', strtotime($dtNow)). ' 00:00:00';
        $D2     = date('Y-m-t', strtotime($dtNow)). ' 23:59:59';    
        
        $array_dashboard         = [];
        $ArrayClientesNuevos     = [] ;
        $ArrayReprestamo         = [] ;
        $Loadarray               = [] ;
        $position_array          = 0 ;

        
        if ($Prom > 0) {
            $Represtamo = Reloan::whereBetween('date_reloan', [$D1, $D2])->where('user_created',$Prom)->get();
        }else{
            $Represtamo = Reloan::whereBetween('date_reloan', [$D1, $D2])->get();
        }
        
        foreach ($Represtamo as $rc) {
            
            $ArrayReprestamo[$position_array] = [
                'id_clientes'       => $rc->id_clientes,
                'Nombre'            => $rc->Clientes->nombre . " " . $rc->Clientes->apellidos,
                'Fecha'             => \Date::parse($rc->date_reloan)->format('D, M d, Y') ,
                'Monto'             => "C$ ".number_format($rc->amount_reloan,2),
                'Origen'            => 'RePrestamo',
            ];
            $Loadarray[$position_array] = $rc->loan_id;
            $position_array++;
        }


        if ($Prom > 0) {
            $Creditos   = Credito::whereBetween('fecha_apertura', [$D1, $D2])->whereNotIn('id_creditos', $Loadarray)->where('asignado',$Prom)->get();
        }else{
            $Creditos   = Credito::whereBetween('fecha_apertura', [$D1, $D2])->whereNotIn('id_creditos', $Loadarray)->get();
        }
        
        foreach ($Creditos as $c) {
            $ArrayClientesNuevos[$position_array] = [
                'id_clientes'       => $c->id_clientes,
                'Nombre'            => $c->Clientes->nombre . " " . $c->Clientes->apellidos,
                'Fecha'             => \Date::parse($c->fecha_apertura)->format('D, M d, Y') ,
                'Monto'             => "C$ ".number_format($c->monto_credito,2),
                'Origen'            => 'Nuevo',
            ];
            $position_array++;
        }

        $SALDOS_COLOCADOS = $Represtamo->sum('amount_reloan') + $Creditos->sum('monto_credito'); 

        $array_dashboard = [
            "CLIENTES_NUEVO"        => $Creditos->count(),
            "RE_PRESTAMOS"          => $Represtamo->count(),
            "SALDOS_COLOCADOS"      => $SALDOS_COLOCADOS,
            "LISTA_CLIENTES"        =>array_merge($ArrayClientesNuevos , $ArrayReprestamo)
        ];

        

        return $array_dashboard;
    }

    public static function getClientesDesembolsados()
    {
        $dtNow  = date('Y-m-d');
        $D1     = date('Y-m-01', strtotime($dtNow)). ' 00:00:00';
        $D2     = date('Y-m-t', strtotime($dtNow)). ' 23:59:59';    
        $role   = Auth::User()->id_rol;
        $Prom   = Auth::id();

        $ArrayClientesNuevos     = [] ;
        $ArrayReprestamo         = [] ;
        $Loadarray               = [] ;
        $position_array          = 0 ;

        $Represtamo = Reloan::where('user_created',$Prom)->whereBetween('date_reloan', [$D1, $D2])->get();
        foreach ($Represtamo as $rc) {
            
            $ArrayReprestamo[$position_array] = [
                'id_clientes'       => $rc->id_clientes,
                'Nombre'            => $rc->Clientes->nombre . " " . $rc->Clientes->apellidos,
                'Fecha'             => \Date::parse($rc->date_reloan)->format('D, M d, Y') ,
                'Monto'             => "C$ ".number_format($rc->amount_reloan,2),
                'Origen'            => 'RePrestamo',
            ];
            $Loadarray[$position_array] = $rc->loan_id;
            $position_array++;
        }

        $Creditos   = Credito::where('asignado',$Prom)->whereBetween('fecha_apertura', [$D1, $D2])->whereNotIn('id_creditos', $Loadarray)->get();
        foreach ($Creditos as $c) {
            $ArrayClientesNuevos[$position_array] = [
                'id_clientes'       => $c->id_clientes,
                'Nombre'            => $c->Clientes->nombre . " " . $c->Clientes->apellidos,
                'Fecha'             => \Date::parse($c->fecha_apertura)->format('D, M d, Y') ,
                'Monto'             => "C$ ".number_format($c->monto_credito,2),
                'Origen'            => 'Nuevo',
            ];
            $position_array++;
        }

        $array_merge = array_merge($ArrayClientesNuevos , $ArrayReprestamo);

        return $array_merge;
    }
    
}
