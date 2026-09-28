<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Modelo de auditoria de actividad (tabla activity_log).
 * El insert lo realiza el middleware App\Http\Middleware\RegistrarActividad.
 * Aqui viven las consultas para la seccion "Monitoreo de Actividad".
 */
class ActivityLog extends Model
{
    public $timestamps = false;
    protected $table   = 'activity_log';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }

    /**
     * Arma la consulta con los filtros comunes (fechas, usuario, tipo).
     */
    private static function buildQuery($dtIni, $dtEnd, $IdUser, $Tipo)
    {
        $query = self::query();

        if ($dtIni && $dtEnd) {
            $query->whereBetween('created_at', [$dtIni . ' 00:00:00', $dtEnd . ' 23:59:59']);
        }

        if ($IdUser) {
            $query->where('user_id', $IdUser);
        }

        if ($Tipo) {
            $query->where('tipo', $Tipo);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Datos para el DataTable (client-side, dataSrc:'').
     * Lee: dtIni, dtEnd (Y-m-d), IdUser, Tipo.
     */
    public static function getData(Request $request)
    {
        $query = self::buildQuery(
            $request->input('dtIni'),
            $request->input('dtEnd'),
            $request->input('IdUser'),
            $request->input('Tipo')
        );

        return $query->get()->map(function ($log) {
            return [
                "Id"           => $log->id,
                "Usuario"      => $log->nombre_usuario ?? (optional($log->user)->nombre ?? 'N/D'),
                "Seccion"      => $log->seccion ?: '-',
                "Tipo"         => $log->tipo ?: '-',
                "Metodo"       => $log->metodo ?: '-',
                "Ruta"         => $log->ruta ?: '-',
                "Registro"     => $log->registro_id ?: '-',
                "Ip"           => $log->ip ?: '-',
                "FechaEntrada" => $log->created_at ? \Date::parse($log->created_at)->format('d-m-Y h:i:s A') : '-',
            ];
        })->toArray();
    }

    /**
     * Exporta a Excel con PHPExcel legacy (mismo patron que LoginLogReport).
     * Lee: dt_ini, dt_end (Y-m-d), IdUser, Tipo.
     */
    public static function ExportExcel(Request $request)
    {
        $query = self::buildQuery(
            $request->input('dt_ini'),
            $request->input('dt_end'),
            $request->input('IdUser'),
            $request->input('Tipo')
        );

        $rows = $query->get();

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setTitle('Monitoreo de Actividad');
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Actividad');

        $styleTitulo = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
            'fill'      => ['type' => \PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER],
        ];
        $styleHeader = [
            'font'      => ['bold' => true, 'color' => ['rgb' => '000000']],
            'fill'      => ['type' => \PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'FFC000']],
            'alignment' => ['horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allborders' => ['style' => \PHPExcel_Style_Border::BORDER_THIN]],
        ];
        $styleBordes = [
            'borders' => ['allborders' => ['style' => \PHPExcel_Style_Border::BORDER_THIN]],
        ];

        // Titulo
        $sheet->mergeCells('A1:H3');
        $sheet->setCellValue('A1', 'CREDINSTANTES - MONITOREO DE ACTIVIDAD');
        $sheet->getStyle('A1:H3')->applyFromArray($styleTitulo);

        // Cabeceras (fila 5)
        $cabeceras = ['FECHA / HORA', 'USUARIO', 'SECCION', 'TIPO', 'METODO', 'RUTA', 'REGISTRO', 'IP'];
        $col = 'A';
        foreach ($cabeceras as $c) {
            $sheet->setCellValue($col . '5', $c);
            $col++;
        }
        $sheet->getStyle('A5:H5')->applyFromArray($styleHeader);

        // Datos (desde fila 6)
        $fila = 6;
        foreach ($rows as $log) {
            $fecha = $log->created_at ? \Date::parse($log->created_at)->format('d-m-Y h:i:s A') : '-';
            $sheet->setCellValue('A' . $fila, $fecha);
            $sheet->setCellValue('B' . $fila, $log->nombre_usuario ?? (optional($log->user)->nombre ?? 'N/D'));
            $sheet->setCellValue('C' . $fila, $log->seccion ?: '-');
            $sheet->setCellValue('D' . $fila, $log->tipo ?: '-');
            $sheet->setCellValue('E' . $fila, $log->metodo ?: '-');
            $sheet->setCellValueExplicit('F' . $fila, $log->ruta ?: '-', \PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $fila, $log->registro_id ?: '-', \PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue('H' . $fila, $log->ip ?: '-');
            $fila++;
        }
        if ($fila > 6) {
            $sheet->getStyle('A6:H' . ($fila - 1))->applyFromArray($styleBordes);
        }

        // Anchos
        $anchos = ['A' => 22, 'B' => 28, 'C' => 22, 'D' => 14, 'E' => 10, 'F' => 40, 'G' => 12, 'H' => 16];
        foreach ($anchos as $c => $w) {
            $sheet->getColumnDimension($c)->setWidth($w);
        }

        $NameFile = "MonitoreoActividad.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $NameFile . '"');
        header('Cache-Control: max-age=0');

        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }
}
