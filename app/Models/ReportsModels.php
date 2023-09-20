<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class ReportsModels extends Model {

    public static function getVisitar(Request $request)
    {
        $Date    = $request->input('Fecha_');
        $Visitas =  RefAbonos::whereDate('FechaPago','=',$Date)->get() ;
        $array_vista = array();

        foreach ($Visitas as $key => $v) {
            $array_vista[$key] = [
                "id_pagoabono" => $v->id_pagoabono,
                "numero_pago" => $v->numero_pago,
                "Nombre"    => $v->Creditos[0]->Clientes->nombre,
                "apellido"    => $v->Creditos[0]->Clientes->apellidos,
                "direccion_domicilio"    => $v->Creditos[0]->Clientes->direccion_domicilio,
                "telefono"    => $v->Creditos[0]->Clientes->telefono,
                "cuota"    => $v->Creditos[0]->cuota
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
            $Abonos = Abono::whereBetween('fecha_cuota', [$dtIni, $dtEnd])->get();
        } else {
            $Abonos = Abono::whereBetween('fecha_cuota', [$dtIni, $dtEnd])->whereHas('credito', function ($query) use ($IdCln) {
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

    
}
