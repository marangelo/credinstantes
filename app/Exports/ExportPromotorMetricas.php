<?php

namespace App\Exports;

use App\Models\ReportsModels;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportPromotorMetricas implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $ID = 40;
        return view('Promotor.exportMetricas', [
            'Clientes' => ReportsModels::getMetricasPromotor($ID)
        ]);
    }
}
