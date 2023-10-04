<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class ReportsModels extends Model {

    public static function getVisitar(Request $request)
    {
        $Date   = $request->input('Fecha_');
        $Day    = date('N', strtotime($Date));
        $Creditos =  Credito::where('id_diassemana',$Day)->where('activo', 1)->get();
     
        $array_vista = array();
        foreach ($Creditos as $key => $v) {
            $array_vista[$key] = [
                "id_pagoabono"           => $v->id_creditos,
                "Nombre"                 => $v->Clientes->nombre,
                "apellido"               => $v->Clientes->apellidos,
                "direccion_domicilio"    => $v->Clientes->direccion_domicilio,
                "telefono"               => $v->Clientes->telefono,
                "cuota"                  => $v->cuota,
                "saldo"                  => $v->saldo,
                "pendiente"              => ($v->abonos->isNotEmpty()) ? $v->abonos->first()->saldo_cuota : 0 
            ];
        }
        return $array_vista;
    }
    public static function getAbonos(Request $request)
    {
        $dtIni    = $request->input('dtIni');
        $dtEnd    = $request->input('dtEnd');
        $IdCln    = $request->input('IdCln');
        
        
        if ($IdCln < 0) {
            $Abonos = Abono::whereBetween('fecha_cuota', [$dtIni, $dtEnd])->where('activo', 1)->get();
        } else {
            $Abonos = Abono::whereBetween('fecha_cuota', [$dtIni, $dtEnd]) ->where('activo', 1)->whereHas('credito', function ($query) use ($IdCln) {
                            $query->where('id_clientes', $IdCln);
                        })->get();
        }
        
        $array_abonos = array();
        foreach ($Abonos as $key => $a) {
            $array_abonos[$key] = [
                "id_abonoscreditos" => $a->id_abonoscreditos,
                "fecha_cuota"       => $a->fecha_cuota,
                "Nombre"            => $a->credito->Clientes->nombre,
                "apellido"          => $a->credito->Clientes->apellidos,
                "cuota_cobrada"     => $a->cuota_cobrada,
                "pago_capital"      => $a->pago_capital,
                "pago_intereses"    => $a->pago_intereses
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
        
        $array_clientes = array();
        foreach ($Clientes as $key => $c) {


            if ($c->tieneCreditoVencido->isNotEmpty()) {
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
                    "nombre" => $c->nombre,
                    "apellidos" => $c->apellidos,
                    "Estado" => $span
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
        $D1     = date('Y-m-01', strtotime($dtNow));
        $D2     = date('Y-m-t', strtotime($dtNow));

        $Abonos     = Abono::whereBetween('fecha_cuota', [$D1, $D2])->where('activo', 1)->get();
        $Clientes   = Clientes::getClientes();
        $Dias       = Abono::selectRaw('DAY(fecha_cuota) as dy, SUM(cuota_cobrada) as total')->whereBetween('fecha_cuota', [$D1, $D2]) ->where('activo', 1)->groupByRaw('DAY(fecha_cuota)')->get();
        
        $ttPagoCapital      = $Abonos->sum('pago_capital');
        $ttPagoIntereses    = $Abonos->sum('pago_intereses');
        $ttCuotaCobrada     = $Abonos->sum('cuota_cobrada');

        foreach ($Dias as $dia) {
            $vLabel[]   = 'D' . $dia->dy; 
            $vData[]    = $dia->total; 
        }

        $array_dashboard = [
            "INGRESO"           => $ttCuotaCobrada,
            "CAPITAL"           => $ttPagoCapital,
            "INTERESES"         => $ttPagoIntereses,
            "clientes_activos"  => $Clientes->count(),
            "label"             => $vLabel,
            "Data"              => $vData
        ];

        return $array_dashboard;
    }
    
}
