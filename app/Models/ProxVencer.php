<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class ProxVencer extends Model {

    public static function getProxVencer(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';
        $array_abonos = array();
    

        $Creditos =  Credito::where('fecha_ultimo_abono', '>=', $dtIni)
                        ->where('fecha_ultimo_abono', '<=', $dtEnd)
                        ->where('saldo','>', 0)
                        ->orderBy('fecha_ultimo_abono', 'ASC')
                        ->get();
        
        foreach ($Creditos as $key => $a) {     
            
            $array_abonos[$key] = [
                "id_abonoscreditos" => $a->id_creditos,
                "fecha_ultimo_abono"       => $a->fecha_ultimo_abono,
                "Nombre"            => strtoupper($a->Clientes->nombre),
                "apellido"          => strtoupper($a->Clientes->apellidos),
                "saldo"             => $a->saldo,
            ];  
        }


        return $array_abonos;
    }

}
