<?php

namespace App\Models;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

class Bitacora extends Model
{
    public static function getBitacora(Request $request)
    {
        $dtIni    = $request->dtIni.' 00:00:00';
        $dtEnd    = $request->dtEnd.' 23:59:59';
        $IdZna    = $request->IdZna;

        $ClientesArchivados = ClientesNA::all()->pluck('id_cliente')->toArray();
        $Creditos = Credito::where('activo', 1)
                ->whereBetween('fecha_apertura', [$dtIni, $dtEnd])
                ->whereIn('estado_credito', [1, 2, 3])
                ->whereNotIn('id_clientes', $ClientesArchivados)
                ->when($IdZna > -1, function ($query) use ($IdZna) {
                    $query->whereHas('Clientes', function ($q) use ($IdZna) {
                        $q->where('id_zona', $IdZna);
                    });
                })
                ->get();      
    

        $Array_Bitacora = array();
        
        foreach ($Creditos as $key => $a) 
        {  
            $NameCliente = $a->Clientes->nombre.' '.$a->Clientes->apellidos;

            $Zona = (empty($a->Clientes->getZona->nombre_zona)) ? 'N/D' : $a->Clientes->getZona->nombre_zona ;

            $Origen = $a->getRefResquest->getRequest->Origen ?? 'N/D';

            $Seguro = (isset($a->Seguro)) ? $a->Seguro : '0';

            $Array_Bitacora[$key] = [
                "Id"                => (string)$a->id_creditos,
                "fecha_arqueo"      => \Date::parse($a->fecha_apertura)->format('d-m-Y') ,
                "Zona"              => strtoupper( $Zona ),
                "id_zona"           => $a->Clientes->id_zona,
                "Nombre"            => strtoupper($NameCliente),                
                "Origen"            => strtoupper($Origen),
                "Seguro"            => $Seguro,
                "Monto"             => $a->monto_credito,
                "Plazo"             => $a->plazo,
                "Cuota"             => $a->cuota,
                "IdSemana"          => $a->getDiasSemana->dia_semana,
                "Interes"           => $a->taza_interes,
            ];
            
        }


        return $Array_Bitacora;
    }
    public static function UpdateSeguro(Request $request)
    {
        $IdCredito = $request->IdCredito;
        $Valor     = $request->Valor;

        return Credito::where('id_creditos', $IdCredito)
            ->update(['Seguro' => $Valor]);
    }
    public static function Export($request)
    {
        $dtIni = $request->dtIni;
        $dtEnd = $request->dtEnd;

        $Creditos = Bitacora::getBitacora($request);
        $Zonas    = Zonas::getZonas();
        
        $objPHPExcel = new PHPExcel();

        /* ================= ESTILOS ================= */
        $titulo = [
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => 'center'],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '2F75B5']
            ]
        ];

        $subtitulo = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => 'center'],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '2F75B5']
            ]
        ];

        $encabezado = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'       => true
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFD966']
            ],
            'borders' => [
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
            ]
        ];

        $borde = [
            'borders' => [
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
            ]
        ];

        /* ================= HOJAS POR ZONA ================= */
        $index = 0;

        foreach ($Zonas as $zona) {

            if ($index > 0) {
                $objPHPExcel->createSheet();
            }

            $sheet = $objPHPExcel->setActiveSheetIndex($index);
            $sheet->setTitle(strtoupper($zona->nombre_zona));

            /* ================= TITULOS ================= */
            $sheet->mergeCells('A1:K1');
            $sheet->setCellValue('A1', 'CREDINSTANTE');
            $sheet->getStyle('A1:K1')->applyFromArray($titulo);

            $sheet->mergeCells('A2:K2');
            $sheet->setCellValue(
                'A2',
                'BITACORA DE DESEMBOLSO ' . strtoupper(\Date::parse($dtIni)->format('F Y'))
            );
            $sheet->getStyle('A2:K2')->applyFromArray($subtitulo);

            /* ================= ENCABEZADOS ================= */
            $row = 4;
            $sheet->fromArray([
                'ORIGEN',
                'FECHA',
                'NOMBRES Y APELLIDOS',
                'MONTO',
                'PLAZO',
                'CUOTA',
                'SEGURO',
                'DIA DE VISITA',
                'N° DE COMPROBANTE',
                'TASA DE %',
                'ZONA'
            ], null, 'A'.$row);

            $sheet->getStyle('A4:K4')->applyFromArray($encabezado);

            /* ================= DETALLE ================= */
            $row++;
            
            foreach ($Creditos as $c) {
                

                if ($c['id_zona'] != $zona->id_zona) {
                    continue;
                }

                $sheet->setCellValue('A'.$row, strtoupper($c['Origen']))
                    ->setCellValue('B'.$row, strtoupper($c['fecha_arqueo']))
                    ->setCellValue('C'.$row, strtoupper($c['Nombre']))
                    ->setCellValue('D'.$row, $c['Monto'])
                    ->setCellValue('E'.$row, $c['Plazo'])
                    ->setCellValue('F'.$row, $c['Cuota'])
                    ->setCellValue('G'.$row, $c['Seguro'])
                    ->setCellValue('H'.$row, strtoupper($c['IdSemana']))
                    ->setCellValue('I'.$row, $c['Id'])
                    ->setCellValue('J'.$row, $c['Interes'].'%')
                    ->setCellValue('K'.$row, strtoupper($zona->nombre_zona));

                $row++;
            }

            /* ================= FORMATOS ================= */
            $sheet->getStyle('E5:E'.$row)->getNumberFormat()
                ->setFormatCode('"C$" #,##0.00');

            $sheet->getStyle('F5:F'.$row)->getNumberFormat()
                ->setFormatCode('"C$" #,##0.00');

            $sheet->getStyle('H5:H'.$row)->getNumberFormat()
                ->setFormatCode('"C$" #,##0.00');

            $sheet->getStyle('A4:K'.$row)->applyFromArray($borde);

            foreach (range('A','K') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $index++;
        }

        /* ================= DESCARGA ================= */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Bitacora_Desembolsos.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
    }

}
