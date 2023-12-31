<?php

namespace App\Exports;

use App\Models\Pagos;
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

        $dtIni = date("Y-m-d", strtotime(str_replace('/', '-', $dtIni))).' 00:00:00';;
        $dtEnd = date("Y-m-d", strtotime(str_replace('/', '-', $dtEnd))).' 23:59:59';;

        $Obj =  Pagos::whereBetween('FECHA_ABONO', [$dtIni, $dtEnd])->Where('activo',1);
    
        if ($IdCln > 0) {
            $Obj->Where('id_clientes',$IdCln);
        }
        
        $Abonos = $Obj->get();

        $array_abonos = [];

        foreach ($Abonos as $key => $a) {

            $Ingreso_neto = $a->CAPITAL + $a->INTERES ;
            
            
            $array_abonos[$key] = [
                "id_abonoscreditos" => $a->id_abonoscreditos,
                "fecha_cuota"       => $a->fecha_cuota,
                "Nombre"            => $a->credito->Clientes->nombre,
                "apellido"          => $a->credito->Clientes->apellidos,
                "cuota_cobrada"     => $Ingreso_neto,
                "pago_capital"      => $a->CAPITAL,
                "pago_intereses"    => $a->INTERES,
            ];
                
        }

        return collect($array_abonos);
    }
}
