<?php

namespace App\Exports;

use App\Models\RefAbonos;
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

        $dtIni    = $this->request->input('Fecha_');
        $dtIni = date("Y-m-d", strtotime(str_replace('/', '-', $dtIni)));
        $Visitas =  RefAbonos::whereDate('FechaPago','=',$dtIni)->get() ;
        $array_vista =[];

        foreach ($Visitas as $key => $v) {
            $array_vista[] = [
                "id_pagoabono" => $v->id_pagoabono,
                "numero_pago" => $v->numero_pago,
                "Nombre"    => $v->Creditos[0]->Clientes->nombre,
                "apellido"    => $v->Creditos[0]->Clientes->apellidos,
                "direccion_domicilio"    => $v->Creditos[0]->Clientes->direccion_domicilio,
                "telefono"    => $v->Creditos[0]->Clientes->telefono,
                "cuota"    => $v->Creditos[0]->cuota
            ];
        }

        return collect($array_vista);
    }
}
