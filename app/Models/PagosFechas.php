<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public static function Pagos($IdCredito)
    {  
        $Abonos = PagosFechas::where('ID_CREDITO',$IdCredito)->get();
        
        $Array = [];

        foreach ($Abonos as $Key => $abono) {
            //
            $Array[$Key] = [
                'NUM_PAGO' => $abono->NUM_PAGO,
                'FECHA_PAGO' => \Date::parse($abono->FECHA_PAGO)->format('Y-m-d'),
                'PENDIENTE' => ($abono->SALDO_PENDIENTE > 0) ? 1 : 0 ,
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
    public static function getMora($Zona, $tipoMora)
    {
        $Creditos = credito::where('activo',1)->where('saldo','>',0);
        $fechaActual = now(); 

        if ($tipoMora == 'atrasada') {
            $Creditos->where('estado_credito', 2);
        } elseif ($tipoMora == 'vencida') {
            $Creditos->where('estado_credito',3);
        }

        $Creditos = $Creditos->get()->pluck('id_creditos');

        $Mora = PagosFechas::whereIn('ID_CREDITO', $Creditos)->whereDate('FECHA_PAGO', '<=', $fechaActual);

        if ($Zona > -1) {
            $Mora->where('ID_ZONA', $Zona);
        }

        return $Mora->sum('SALDO_PENDIENTE');
    }



}
