<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Auth;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

class GastosOperaciones extends Model {
    public $timestamps = false;
    protected $table = "tbl_gastos_operaciones";
    protected $primaryKey = 'id_gasto_operaciones';

    public function Creado()
    {
        return $this->belongsTo(Usuario::class, 'id_user','id');
    }

    public static function getGasto(Request $request)
    {
        $ID = $request->input('IdGasto');
        $Obj = GastosOperaciones::where('id_gasto_operaciones', $ID)->get();
        $array_gasto_ope = array();
        foreach ($Obj as $key => $a) {
            $array_gasto_ope[$key] = [
                "Id" => $a->id_gasto_operaciones,
                "Fecha_gasto" => \Date::parse($a->fecha_gasto)->format('d/m/Y'),
                "Concepto" => $a->concepto,
                "Monto" => $a->monto,
                "Usuario" => $a->Creado->nombre,
            ];
        }
        return $array_gasto_ope;
    }

    public static function getGastosOperaciones(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';

        $Obj =  GastosOperaciones::whereBetween('fecha_gasto', [$dtIni, $dtEnd])->Where('activo',1)->get();

        $array_gasto_ope = array();
        
        foreach ($Obj as $key => $a) {  

            $array_gasto_ope[$key] = [
                "Id"            => $a->id_gasto_operaciones,
                "Fecha_gasto"   => \Date::parse($a->fecha_gasto)->format('d-m-Y') ,
                "Concepto"      => $a->concepto,
                "Monto"         => $a->monto,
                "Usuario"       => $a->Creado->nombre,
            ];
                
        }
        return $array_gasto_ope;
    }
    public static function SaveGastoOperaciones(Request $request)
    {
        if ($request->ajax()) {
            try {
                $ID = $request->input('_IdGasto');

                if ($ID == '0') {
                    $response = GastosOperaciones::insert([
                        'concepto'      => $request->input('_Concepto'),
                        'fecha_gasto'   => $request->input('_Fecha'),
                        'monto'         => $request->input('_Monto'),
                        'id_user'       => Auth::id(),
                        'activo'        => 1,
                    ]);
                } else {
                    $response = GastosOperaciones::where('id_gasto_operaciones', $ID)->update([
                        'concepto'      => $request->input('_Concepto'),
                        'fecha_gasto'   => $request->input('_Fecha'),
                        'monto'         => $request->input('_Monto'),
                    ]);
                }

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function RemoveGasto(Request $request)
    {
        if ($request->ajax()) {
            try {
                $ID         = $request->input('IdGasto');
                
                $response =   GastosOperaciones::where('id_gasto_operaciones',  $ID)->update([
                    "activo" => 0,
                ]);
    
                return $response;
    
    
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }

    public static function ExportGastos(Request $request)
    {
        $dt_ini = $request->input('dt_ini') . ' 00:00:00';
        $dt_end = $request->input('dt_end') . ' 23:59:59';

        $gastos = GastosOperaciones::select('concepto', 'fecha_gasto', 'monto')
            ->whereBetween('fecha_gasto', [$dt_ini, $dt_end])
            ->where('activo', 1)
            ->orderBy('fecha_gasto', 'asc')
            ->get();

        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $filaInicioDatos = 6;
        $filaEncabezado = 5;
        $filaTotal = $filaInicioDatos + $gastos->count();

        $titulo = 'CREDINSTANTES GASTOS OPERATIVOS ' . strtoupper(\Carbon\Carbon::parse($dt_ini)->translatedFormat('F Y'));

        // =========================
        // ESTILOS
        // =========================
        $styleTitulo = [
            'font' => [
                'name'  => 'Tahoma',
                'bold'  => true,
                'size'  => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
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
                'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '92D050']
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'       => true
            ],
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $styleBordes = [
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $styleTotal = [
            'font' => [
                'bold' => true
            ],
            'fill' => [
                'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'D9EAD3']
            ],
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $formatMoneda = '_-"C$"* #,##0.00_-;_-"C$"* #,##0.00_-;_-"C$"* "-"??_-;_-@_-';
        $formatFecha = 'dd/mm/yyyy';

        // =========================
        // TITULO
        // =========================
        $sheet->mergeCells('A1:C3');
        $sheet->setCellValue('A1', $titulo);
        $sheet->getStyle('A1:C3')->applyFromArray($styleTitulo);

        // =========================
        // ENCABEZADOS
        // =========================
        $sheet->setCellValue('A5', 'CONCEPTO')
            ->setCellValue('B5', 'FECHA')
            ->setCellValue('C5', 'MONTO');

        $sheet->getStyle('A5:C5')->applyFromArray($styleHeader);

        // =========================
        // ANCHOS DE COLUMNAS
        // =========================
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);

        // =========================
        // DATOS
        // =========================
        $fila = $filaInicioDatos;
        foreach ($gastos as $gasto) {
            $sheet
                ->setCellValue("A{$fila}",  $gasto['concepto'])
                ->setCellValue("B{$fila}",  $gasto['fecha_gasto'])
                ->setCellValue("C{$fila}",  $gasto['monto'] ?? 0);
            $fila++;
        }

        // =========================
        // TOTAL
        // =========================
        $total = number_format($gastos->sum('monto'), 2, '.', ',');    

        $sheet->setCellValue("B{$filaTotal}", 'TOTAL')
            ->setCellValue("C{$filaTotal}", $total);

        $sheet->getStyle("A{$filaInicioDatos}:C{$filaTotal}")->applyFromArray($styleBordes);
        $sheet->getStyle("B{$filaTotal}:C{$filaTotal}")->applyFromArray($styleTotal);

        // =========================
        // FORMATOS
        // =========================
        if ($gastos->count() > 0) {
            $sheet->getStyle("B{$filaInicioDatos}:B" . ($filaTotal - 1))
                ->getNumberFormat()
                ->setFormatCode($formatFecha);

            $sheet->getStyle("C{$filaInicioDatos}:C{$filaTotal}")
                ->getNumberFormat()
                ->setFormatCode($formatMoneda);

            $sheet->getStyle("C{$filaInicioDatos}:C{$filaTotal}")
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        }

        $sheet->setTitle('Gastos Operativos');

        // =========================
        // DESCARGA
        // =========================
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $NameFile = "GastosOperativos_" . $meses[date('n')-1] . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $NameFile . '"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }
}