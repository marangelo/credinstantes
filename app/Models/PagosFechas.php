<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class PagosFechas extends Model {
    public $timestamps = false;
    protected $table = "view_fecha_pagos";    
    public static function getFechasPagos($IdCredito)
    {  
        $Abonos = PagosFechas::where('ID_CREDITO',$IdCredito)->get();
        
        $Array = [];

        foreach ($Abonos as $Key => $abono) {
            //
            $Array[$Key] = [
                'NUM_PAGO' => $abono->NUM_PAGO,
                'FECHA_PAGO' => \Date::parse($abono->FECHA_PAGO)->format('D, M d, Y'),
                'FECHA_ABONO' => (is_null($abono->FECHA_ABONO)) ? 'N/D': \Date::parse($abono->FECHA_ABONO)->format('D, M d, Y'),
                'SALDO_PENDIENTE' => $abono->SALDO_PENDIENTE,
            ];

        }

        return $Array;
    }

    public static function getSaldoPendiente($IdCredito)
    {
        $fechaActual = now(); // Obtener la fecha y hora actual

        $sumaSaldoPendiente = PagosFechas::where('ID_CREDITO', $IdCredito)
            ->whereDate('FECHA_PAGO', '<=', $fechaActual)
            ->sum('SALDO_PENDIENTE');


        return $sumaSaldoPendiente;
    }

    public static function getAbonosPendientes($IdCredito)
    {
        $fechaActual = now(); // Obtener la fecha y hora actual

        $AbonosPendientes = PagosFechas::where('ID_CREDITO', $IdCredito)
            ->where('SALDO_PENDIENTE', '>', 0)->get();


        return $AbonosPendientes;
    }
    public static function getMoraAtrasada()
    {
        $fechaActual = now(); // Obtener la fecha y hora actual

        $Creditos = EstadosMonitor::where('CREDITO_ACTIVO',1)
                    ->where('SALDO_CREDITO','>',0)
                    ->get()
                    ->pluck('ID_CREDITO');

        $MoraPendiente = PagosFechas::whereIn('ID_CREDITO',$Creditos)->whereDate('FECHA_PAGO', '<=', $fechaActual)
            // ->get()
            // ->toArray();
            ->sum('SALDO_PENDIENTE');


        return $MoraPendiente;
    }
    public static function getMoraVencida()
    {
        $fechaActual = now(); // Obtener la fecha y hora actual

        $Creditos = EstadosMonitor::where('CREDITO_ACTIVO',1)
                    ->where('SALDO_CREDITO','>',0)
                    ->where('DIAS_PARA_VENCER','<',0)
                    ->get()
                    ->pluck('ID_CREDITO');

        $MoraVencida = PagosFechas::whereIn('ID_CREDITO',$Creditos)->whereDate('FECHA_PAGO', '<=', $fechaActual)
            //->get()
            //->toArray();
            ->sum('SALDO_PENDIENTE');


        return $MoraVencida;
    }


}
