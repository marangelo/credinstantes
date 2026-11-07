<?php

namespace App\Models;

use Illuminate\Http\Request;

class SolicDenegadas
{
    public static function getSolicDenegadas(Request $request)
    {
        $dtIni = $request->input('dtIni');
        $dtEnd = $request->input('dtEnd');

        $query = RequestsCredit::where('activo', 2)->where('Origen', 'Nueva')
            ->with('User');

        if ($dtIni && $dtEnd) {
            $query->whereBetween('req_start_date', [$dtIni . ' 00:00:00', $dtEnd . ' 23:59:59']);
        }

        $IdUser = $request->input('IdUser');
        if ($IdUser) {
            $query->where('created_by', $IdUser);
        }

        return $query->get()->map(function ($c) {
            return [
                "Id"              => $c->id_req,
                "Cliente"         => strtoupper(trim(($c->first_name ?? '') . ' ' . ($c->last_name ?? ''))),
                "FechaSolicitud"  => \Date::parse($c->req_start_date)->format('d-m-Y'),
                "MontoSolicitado" => $c->monto,
                "Usuario"         => $c->User->nombre ?? '-',
            ];
        })->toArray();
    }

    public static function ReactivarSolicDenegada(Request $request)
    {
        if ($request->ajax()) {
            try {
                $ID = $request->input('IdSolic');
                RequestsCredit::where('id_req', $ID)->update(['activo' => 1]);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public static function ExportSolicDenegadas(Request $request)
    {
        $dt_ini = $request->input('dt_ini');
        $dt_end = $request->input('dt_end');

        $query = RequestsCredit::where('activo', 2)->where('Origen', 'Nueva')->with('User');

        if ($dt_ini && $dt_end) {
            $query->whereBetween('req_start_date', [$dt_ini . ' 00:00:00', $dt_end . ' 23:59:59']);
        }

        $IdUser = $request->input('IdUser');
        if ($IdUser) {
            $query->where('created_by', $IdUser);
        }

        $denegadas = $query->get()->map(function ($c) {
            return [
                "Cliente"         => strtoupper(trim(($c->first_name ?? '') . ' ' . ($c->last_name ?? ''))),
                "MontoSolicitado" => $c->monto,
                "FechaSolicitud"  => \Date::parse($c->req_start_date)->format('d-m-Y'),
                "Usuario"         => $c->User->nombre ?? '-',
            ];
        })->toArray();

        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $filaInicioDatos = 6;
        $filaTotal = $filaInicioDatos + count($denegadas);

        $titulo = 'CREDINSTANTES SOLICITUDES DENEGADAS';

        $styleTitulo = [
            'font' => [
                'name'  => 'Tahoma',
                'bold'  => true,
                'size'  => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'type'  => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allborders' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $styleHeader = [
            'font' => [
                'name' => 'Arial',
                'bold' => true,
                'size' => 10,
            ],
            'fill' => [
                'type'  => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFC000']
            ],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'       => true
            ],
            'borders' => [
                'allborders' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $styleBordes = [
            'borders' => [
                'allborders' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $styleTotal = [
            'font' => ['bold' => true],
            'fill' => [
                'type'  => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFF2CC']
            ],
            'borders' => [
                'allborders' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $formatMoneda = '_-"C$"* #,##0.00_-;_-"C$"* #,##0.00_-;_-"C$"* "-"??_-;_-@_-';

        $sheet->mergeCells('A1:D3');
        $sheet->setCellValue('A1', $titulo);
        $sheet->getStyle('A1:D3')->applyFromArray($styleTitulo);

        $sheet->setCellValue('A5', 'CLIENTE')
            ->setCellValue('B5', 'MONTO SOLICITADO')
            ->setCellValue('C5', 'FECHA SOLICITUD')
            ->setCellValue('D5', 'CREADO POR');

        $sheet->getStyle('A5:D5')->applyFromArray($styleHeader);

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);

        $fila = $filaInicioDatos;
        foreach ($denegadas as $item) {
            $sheet
                ->setCellValue("A{$fila}", $item['Cliente'])
                ->setCellValue("B{$fila}", $item['MontoSolicitado'] ?? 0)
                ->setCellValue("C{$fila}", $item['FechaSolicitud'])
                ->setCellValue("D{$fila}", $item['Usuario']);
            $fila++;
        }

        $total = number_format(collect($denegadas)->sum('MontoSolicitado'), 2, '.', ',');
        $sheet->setCellValue("A{$filaTotal}", 'TOTAL')
            ->setCellValue("B{$filaTotal}", $total);

        $sheet->getStyle("A{$filaInicioDatos}:D{$filaTotal}")->applyFromArray($styleBordes);
        $sheet->getStyle("A{$filaTotal}:B{$filaTotal}")->applyFromArray($styleTotal);

        if (count($denegadas) > 0) {
            $sheet->getStyle("B{$filaInicioDatos}:B" . ($filaTotal - 1))
                ->getNumberFormat()
                ->setFormatCode($formatMoneda);

            $sheet->getStyle("B{$filaInicioDatos}:B{$filaTotal}")
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        }

        $sheet->setTitle('SolicDenegadas');

        $NameFile = "SolicitudesDenegadas.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $NameFile . '"');
        header('Cache-Control: max-age=0');

        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }
}
