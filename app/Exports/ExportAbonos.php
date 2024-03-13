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

            $CAPITAL  = (strtotime($a->FECHA_ABONO) > strtotime("2024-03-13")) ? $a->CAPITAL : $a->CAPITAL_OLD;

            $Ingreso_neto = $CAPITAL + $a->INTERES ;
            
            
            $array_abonos[$key] = [
                "id_abonoscreditos" => $a->id_abonoscreditos,
                "fecha_cuota"       => $a->FECHA_ABONO,
                "Nombre"            => $a->credito->Clientes->nombre,
                "apellido"          => $a->credito->Clientes->apellidos,
                "cuota_cobrada"     => $Ingreso_neto,
                "pago_capital"      => $CAPITAL,
                "pago_intereses"    => $a->INTERES,
            ];
                
        }

        return collect($array_abonos);
    }
}
