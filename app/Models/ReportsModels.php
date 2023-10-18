<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class ReportsModels extends Model {

    public static function getVisitar(Request $request)
    {
        $Date   = $request->input('Fecha_').' 00:00:00';

        $DiaW_   = $request->input('DiaW_');
        $Zona_   = $request->input('Zona_');

        //$Day    = date('N', strtotime($Date));

        $Obj =  Credito::where('activo', 1);

        // $Obj->Where(function($query) use ($Date) {
        //     $query->whereHas('RefAbonos', function ($query) use ($Date) {
        //         $query->where('FechaPago', $Date);
        //     });
        // });

        if ($DiaW_ > 0) {
            $Obj->Where('id_diassemana',$DiaW_);
        }

        if ($Zona_ > 0) {
            $Obj->Where(function($query) use ($Zona_) {
                $query->whereHas('Clientes', function ($query) use ($Zona_) {
                    $query->where('id_zona', $Zona_);
                });
            });
        }

        $Creditos = $Obj->get();

        

        $array_vista = array();
        foreach ($Creditos as $key => $v) {


            $array_vista[$key] = [
                "id_pagoabono"           => $v->id_creditos,
                "Nombre"                 => $v->Clientes->nombre,
                "apellido"               => $v->Clientes->apellidos,
                "direccion_domicilio"    => $v->Clientes->direccion_domicilio,
                "zona"                   => $v->Clientes->getZona->nombre_zona,
                "telefono"               => $v->Clientes->telefono,
                "cuota"                  => $v->cuota,
                "saldo"                  => $v->saldo,
                //"pendiente"              => ($v->abonos->isNotEmpty()) ? $v->abonos->first()->saldo_cuota : 0 ,
                "pendiente"              => $v->AbonoLogs->isNotEmpty() ? $v->AbonoLogs->first()->SALDO_PENDIENTE : 0,
                "Estado"                 => strtoupper($v->Estado->nombre_estado)
            ];
        }
        return $array_vista;
    }
    public static function getAbonos(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';
        $IdCln    = $request->input('IdCln');

        
        if ($IdCln < 0) {
            $Abonos = Pagos::whereBetween('FECHA_ABONO', [$dtIni, $dtEnd]);
        } else {
            $Abonos = Pagos::whereBetween('FECHA_ABONO', [$dtIni, $dtEnd])->Where('id_clientes',$IdCln);
        }

        $Abonos = $Abonos->Where('activo',1)->get();
        
        $array_abonos = array();
        foreach ($Abonos as $key => $a) {

            $Ingreso_neto = $a->CAPITAL + $a->INTERES ;
            
            
            $array_abonos[$key] = [
                "id_abonoscreditos" => $a->id_abonoscreditos,
                "fecha_cuota"       => $a->fecha_cuota,
                "Nombre"            => $a->credito->Clientes->nombre,
                "apellido"          => $a->credito->Clientes->apellidos,
                "cuota_cobrada"     => $Ingreso_neto,
                "pago_capital"      => $a->CAPITAL,
                "pago_intereses"    => $a->INTERES,
            ];
                
        }


        return $array_abonos;
    }
    public static function getMorosidad(Request $request)
    {
        
        
        $Clientes = Clientes::getClientes();

        $span = '';
        $Color = '';
        $isAdded =  false;
        $IdCreditoVencido = 0 ;
        
        $array_clientes = [];
        foreach ($Clientes as $key => $c) {


            if ($c->tieneCreditoVencido->isNotEmpty()) {
                $IdCreditoVencido = $c->tieneCreditoVencido->first()->id_creditos;
                switch ($c->tieneCreditoVencido->first()->estado_credito) {
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
                $span = '<span class="badge '.$Color.' "> '.$c->tieneCreditoVencido->first()->Estado->nombre_estado.'</span>';
            } else {
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
                
            }

            if ($isAdded) {
                $array_clientes[] = [
                    "IdCliente"     => $c->id_clientes,
                    "nombre"        => $c->nombre,
                    "apellidos"     => $c->apellidos,
                    "Estado"        => $span,
                    "IdCredito"     => $IdCreditoVencido,

                ];
            }
            
        
        }

        return $array_clientes;
    }

    public static function getDashboard(Request $request)
    {
        $array_dashboard = [];
        $vLabel          = [];
        $vData           = [];

        $dtNow  = date('Y-m-d');
        $D1     = date('Y-m-01', strtotime($dtNow)). ' 01:00:00';
        $D2     = date('Y-m-t', strtotime($dtNow)). ' 23:59:59';        

        $Abonos     = Abono::whereBetween('fecha_cuota_secc1', [$D1, $D2])
                            ->orWhereBetween('fecha_cuota_secc2', [$D2, $D2])
                            ->where('activo', 1)->get();
        
        $Clientes   = Clientes::getClientes();

        $Dias       = Abono::selectRaw('DAY(fecha_cuota) as dy, SUM(cuota_cobrada) as total')
                        ->whereBetween('fecha_cuota_secc1', [$D1, $D2])
                        ->orWhereBetween('fecha_cuota_secc2', [$D2, $D2])
                        ->where('activo', 1)
                        ->groupByRaw('DAY(fecha_cuota)')
                        ->get();
        
        $ttPagoCapital      = $Abonos->sum('pago_capital');
        $ttPagoIntereses    = $Abonos->sum('pago_intereses');
        $ttCuotaCobrada     = $Abonos->sum('cuota_cobrada');

        foreach ($Dias as $dia) {
            $vLabel[]   = 'D' . $dia->dy; 
            $vData[]    = $dia->total; 
        }

        $Saldos_Cartera = Credito::where('activo',1)->sum('saldo');


        $array_dashboard = [
            "INGRESO"           => $ttCuotaCobrada,
            "CAPITAL"           => $ttPagoCapital,
            "INTERESES"         => $ttPagoIntereses,
            "SALDOS_CARTERA"    => $Saldos_Cartera,
            "clientes_activos"  => $Clientes->count(),
            "label"             => $vLabel,
            "Data"              => $vData
        ];

        return $array_dashboard;
    }
    
}
