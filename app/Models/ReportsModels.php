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
}
