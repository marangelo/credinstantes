<?php

namespace App\Exports;

use App\Models\Credito;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportVisitar implements FromCollection
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {

        $Date   = $this->request->input('Fecha_');
        $Date   = date("Y-m-d", strtotime(str_replace('/', '-', $Date)));
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

        return collect($array_vista);
    }
}
