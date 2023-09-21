<?php

namespace App\Exports;

use App\Models\Abono;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportAbonos implements FromCollection
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $dtIni = $this->request->input('nmIni');
        $dtEnd = $this->request->input('nmEnd');
        $IdCln = $this->request->input('IdCln');

        $dtIni = date("Y-m-d", strtotime(str_replace('/', '-', $dtIni)));
        $dtEnd = date("Y-m-d", strtotime(str_replace('/', '-', $dtEnd)));

        if ($IdCln < 0) {
            $Abonos = Abono::whereBetween('fecha_cuota', [$dtIni, $dtEnd])->get();
        } else {
            $Abonos = Abono::whereBetween('fecha_cuota', [$dtIni, $dtEnd])->whereHas('credito', function ($query) use ($IdCln) {
                $query->where('id_clientes', $IdCln);
            })->get();
        }

        $array_abonos = [];

        foreach ($Abonos as $a) {
            $array_abonos[] = [
                "id_abonoscreditos" => $a->id_abonoscreditos,
                "Nombre" => $a->credito->Clientes->nombre. ' ' . $a->credito->Clientes->apellidos,
                "cuota_cobrada" => $a->cuota_cobrada,
                "pago_capital" => $a->pago_capital,
                "pago_intereses" => $a->pago_intereses
            ];
        }

        return collect($array_abonos);
    }
}
