<?php

namespace App\Models;

use Illuminate\Http\Request;

class LoginLogReport
{
    public static function getData(Request $request)
    {
        $dtIni = $request->input('dtIni');
        $dtEnd = $request->input('dtEnd');

        $query = LoginLog::query();

        if ($dtIni && $dtEnd) {
            $query->whereBetween('created_at', [$dtIni . ' 00:00:00', $dtEnd . ' 23:59:59']);
        }

        $IdUser = $request->input('IdUser');
        if ($IdUser) {
            $query->where('user_id', $IdUser);
        }

        $query->orderBy('created_at', 'desc');

        return $query->get()->map(function ($log) {
            return [
                "Id"              => $log->id,
                "Usuario"         => optional($log->user)->nombre ?? 'N/D',
                "Ip"              => $log->ip ?? '-',
                "Browser"         => ($log->browser ? $log->browser . ($log->browser_version ? ' v' . $log->browser_version : '') : '-'),
                "Platform"        => ($log->platform ?: '-'),
                "Device"          => ($log->device ?: '-'),
                "DeviceModel"     => ($log->device_model ?: '-'),
                "FechaAcceso"     => \Date::parse($log->created_at)->format('d-m-Y h:i A'),
            ];
        })->toArray();
    }

    public static function ExportExcel(Request $request)
    {
        $dtIni = $request->input('dt_ini');
        $dtEnd = $request->input('dt_end');

        $query = LoginLog::query();

        if ($dtIni && $dtEnd) {
            $query->whereBetween('created_at', [$dtIni . ' 00:00:00', $dtEnd . ' 23:59:59']);
        }

        $IdUser = $request->input('IdUser');
        if ($IdUser) {
            $query->where('user_id', $IdUser);
        }

        $query->orderBy('created_at', 'desc');

        $data = $query->get()->map(function ($log) {
            return [
                "Usuario"     => optional($log->user)->nombre ?? 'N/D',
                "Ip"          => $log->ip ?? '-',
                "Browser"     => ($log->browser ? $log->browser . ($log->browser_version ? ' v' . $log->browser_version : '') : '-'),
                "Platform"    => ($log->platform ?: '-'),
                "Device"      => ($log->device ?: '-'),
                "DeviceModel" => ($log->device_model ?: '-'),
                "Fecha"       => \Date::parse($log->created_at)->format('d-m-Y h:i A'),
            ];
        })->toArray();

        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $filaInicioDatos = 6;
        $filaTotal = $filaInicioDatos + count($data);

        $titulo = 'CREDINSTANTES - REPORTE DE ACCESOS';

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

        $sheet->mergeCells('A1:G3');
        $sheet->setCellValue('A1', $titulo);
        $sheet->getStyle('A1:G3')->applyFromArray($styleTitulo);

        $sheet->setCellValue('A5', 'USUARIO')
            ->setCellValue('B5', 'IP')
            ->setCellValue('C5', 'NAVEGADOR')
            ->setCellValue('D5', 'PLATAFORMA')
            ->setCellValue('E5', 'DISPOSITIVO')
            ->setCellValue('F5', 'MODELO')
            ->setCellValue('G5', 'FECHA ACCESO');

        $sheet->getStyle('A5:G5')->applyFromArray($styleHeader);

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(22);

        $fila = $filaInicioDatos;
        foreach ($data as $item) {
            $sheet
                ->setCellValue("A{$fila}", $item['Usuario'])
                ->setCellValue("B{$fila}", $item['Ip'])
                ->setCellValue("C{$fila}", $item['Browser'])
                ->setCellValue("D{$fila}", $item['Platform'])
                ->setCellValue("E{$fila}", $item['Device'])
                ->setCellValue("F{$fila}", $item['DeviceModel'])
                ->setCellValue("G{$fila}", $item['Fecha']);
            $fila++;
        }

        $sheet->getStyle("A{$filaInicioDatos}:G{$filaTotal}")->applyFromArray($styleBordes);

        $sheet->setTitle('Accesos');

        $NameFile = "ReporteAccesos.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $NameFile . '"');
        header('Cache-Control: max-age=0');

        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }
}
