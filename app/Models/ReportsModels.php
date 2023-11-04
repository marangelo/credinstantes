<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class ReportsModels extends Model {

    public static function getVisitar(Request $request)
    {
        $Date   = $request->input('Fecha_').' 00:00:00';

        //$DiaW_   = $request->input('DiaW_');
        $Zona_   = $request->input('Zona_');

        $Day    = date('N', strtotime($Date));

        $Obj =  Credito::where('activo', 1);

    
        if ($Day > 0) {
            $Obj->Where('id_diassemana',$Day);
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
                "Nombre"                 => $v->Clientes->nombre. ' ' . $v->Clientes->apellidos,
                "direccion_domicilio"    => $v->Clientes->direccion_domicilio,
                "zona"                   => $v->Clientes->getZona->nombre_zona,
                "telefono"               => $v->Clientes->telefono,
                "cuota"                  => $v->cuota,
                "saldo"                  => $v->saldo,
                //"pendiente"              => ($v->abonos->isNotEmpty()) ? $v->abonos->first()->saldo_cuota : 0 ,
                "today"                  => $v->AbonoToday->isNotEmpty() ? $v->AbonoToday->first()->tDay : 0,
                "pendiente"              => $v->AbonoLogs->isNotEmpty() ? $v->AbonoLogs->first()->SALDO_PENDIENTE : 0,
                "Estado"                 => strtoupper($v->Estado->nombre_estado)
            ];
        }
        return $array_vista;
    }
    public static function getAbonos(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 00:00:00';
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
        $IdZna    = $request->input('IdZna');
        
        $Clientes = Clientes::getClientes();

        if ($IdZna > 0) {
            $Clientes = Clientes::getClientes()->Where('id_zona',$IdZna);
        }else{
            $Clientes = Clientes::getClientes();
        }


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

                $cuota = $c->getCreditos->isNotEmpty() ? $c->getCreditos->first()->cuota : 0;
                $saldo = $c->getCreditos->isNotEmpty() ? $c->getCreditos->first()->saldo : 0;
                $pendi = 0 ;


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

    public static function getDashboard(Request $request)
    {
        $array_dashboard = [];
        $vLabel          = [];
        $vData           = [];

        $dtNow  = date('Y-m-d');
        $D1     = date('Y-m-01', strtotime($dtNow)). ' 00:00:00';
        $D2     = date('Y-m-t', strtotime($dtNow)). ' 00:00:00';        

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
