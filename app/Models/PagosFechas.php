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

}
