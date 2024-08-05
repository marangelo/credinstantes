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
        $IdZna    = $request->input('IdZna');
        $array_abonos = array();

        $Obj = Credito::whereBetween('fecha_ultimo_abono', [$dtIni, $dtEnd])->Where('activo',1);
        
        if($IdZna > 0){
            $Obj->whereHas('Clientes', function ($query) use ($IdZna) {
                $query->where('id_zona', $IdZna);
            });
        }

        $Creditos = $Obj->get();
        
        foreach ($Creditos as $key => $a) {     
            
            $array_abonos[$key] = [
                "id_abonoscreditos"     => $a->id_creditos,
                "ZONA"                  => $a->Clientes->getZona->nombre_zona,
                "fecha_ultimo_abono"    => date('d/m/Y', strtotime($a->fecha_ultimo_abono)),
                "Nombre"                => strtoupper($a->Clientes->nombre),
                "apellido"              => strtoupper($a->Clientes->apellidos),
                "saldo"                 => $a->saldo,
            ];  
        }

        return $array_abonos;
    }

}
