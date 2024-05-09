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
                "Usuario"       => $a->id_user,
            ];
                
        }
        return $array_gasto_ope;
    }
    public static function SaveGastoOperaciones(Request $request)
    {
        if ($request->ajax()) {
            try {

                $response = GastosOperaciones::insert([
                    'concepto'      => $request->input('_Concepto'),
                    'fecha_gasto'   => $request->input('_Fecha'),
                    'monto'         => $request->input('_Monto'),
                    'id_user'       => Auth::id(),
                    'activo'        => 1,
                ]); 

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
        
        $objPHPExcel = new PHPExcel();
        $tituloReporte = "";
        $titulosColumnas = array();

        $dt_ini = $request->input('dt_ini');
        $dt_end = $request->input('dt_end');


        $Gastos     = GastosOperaciones::WhereBetween('fecha_gasto', [$dt_ini, $dt_end])->Where('activo',1)->get();

        $num_row    = $Gastos->count() + 5;
    
        $estiloTituloColumnas = array(
            'font' => array(
                        'name'  => 'Arial',
                        'bold'  => true,
                        'size'      => 10,
            ),
            'alignment' =>  array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                'wrap'          => TRUE
                            ),
            'borders' => array(
                            'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
            'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,)
            )
        );
                
        $estiloInformacion = new PHPExcel_Style();
        $estiloInformacion->applyFromArray(
            array(
                'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
                )
            )
        );


        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:C3');
        $style = array(
            'font' => array(
            'name'      => 'Tahoma',
            'bold'      => true,
            'italic'    => false,
            'strike'    => false,
            'size'      => 12,
            'color'     => array('rgb' => 'FFFFFF')
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '4472C4') 
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:C3')->applyFromArray($style);
    
        $color_totales = array(                   
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '92D050') 
            )
        );
        
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', "CREDINSTANTES GASTOS OPERATIVOS ". strtoupper(\Date::parse($dt_ini)->format('d F'))) 
        ->setCellValue('A5',  'CONCEPTO')
        ->setCellValue('B5',  'FECHA')
        ->setCellValue('C5',  'MONTO');
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A5:C5')->applyFromArray($color_totales);

        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        
        $objPHPExcel->getActiveSheet()->getStyle('A5:C5')->applyFromArray($estiloTituloColumnas);      

        $i=6;
        $ttTOTAL = 0;
        foreach ($Gastos as $g ){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $g->concepto)
            ->setCellValue('B'.$i,  $g->fecha_gasto)
            ->setCellValue('C'.$i,  $g->monto);
            $ttTOTAL += $g->monto;
            $i++;
        }
        
        $i = $i + 2 ; 


        $formatCode = '_-"$"* #,##0.00_-;_-"$"* #,##0.00_-;_-"$"* "-"??_-;_-@_-';
        $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  '')
                ->setCellValue('B'.$i,  'TOTAL')
                ->setCellValue('C'.$i,  number_format($ttTOTAL,2,'.',''));
                
        
        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A6:C".$num_row);
        $objPHPExcel->getActiveSheet()->getStyle('C6:C6')->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('C6:C6')->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('B6:C'.$num_row)->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('B6:C'.$num_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="GastosOperativos.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        
        
    }
}