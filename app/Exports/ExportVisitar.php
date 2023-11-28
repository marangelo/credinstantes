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

        //$DiaW_   = $request->input('DiaW_');
        $Zona_   = $this->request->input('name_Zona');
       
        $Day    = date('N', strtotime($Date));

        $Obj =  Credito::where('activo', 1);

    
        if ($Day > 0) {
            $Obj->Where('id_diassemana',$Day);
        }

        if ($Zona_ > 0) {
            $Obj->Where(function($query) use ($Zona_) {
                $query->whereHas('Clientes', function ($query) use ($Zona_) {
                    $query->where('id_zona', $Zona_);
                });
            });
        }

        $Creditos = $Obj->get();
        $array_vista = array();
        foreach ($Creditos as $key => $v) {
            $array_vista[$key] = [
                "id_pagoabono"           => $v->id_creditos,
                "Nombre"                 => $v->Clientes->nombre,
                "apellido"               => $v->Clientes->apellidos,
                "direccion_domicilio"    => $v->Clientes->direccion_domicilio,
                "zona"                   => $v->Clientes->getZona->nombre_zona,
                "telefono"               => $v->Clientes->telefono,
                "cuota"                  => $v->cuota,
                "saldo"                  => $v->saldo,
                //"pendiente"              => ($v->abonos->isNotEmpty()) ? $v->abonos->first()->saldo_cuota : 0 ,
                "today"                  => $v->AbonoToday->isNotEmpty() ? $v->AbonoToday->first()->tDay : 0,
                "pendiente"              => $v->AbonoLogs->isNotEmpty() ? $v->AbonoLogs->first()->SALDO_PENDIENTE : 0,
                "Estado"                 => strtoupper($v->Estado->nombre_estado)
            ];
        }


        return collect($array_vista);
    }
}
