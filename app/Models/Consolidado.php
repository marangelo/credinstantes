<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

class Consolidado extends Model {
    public $timestamps = false;
    protected $table = "tbl_consolidados";

    public function ConsolidadoCategoria()
    {
        return $this->hasMany(ConsolidadoCat::class, 'key', 'Concepto');
    }

    //CREA UNA FUNCIONA STATIC PARA LEER UN PROCEDURE QUE SE LLAMA CalcConsolidado Y RECIBE COMO PARAMETRO EL YEAR ACTUAL
    public static function CalcConsolidado($year){
        try {
            $json_arrays = array();
            $i = 0 ;

            $months = array(
                //'Jan', 'Feb', 'Mar', 'Apr', 'May', 
                'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            );
            foreach($months as $m){
                $json_arrays['header_date'][$i] = $m . substr($year, -2);
                $i++;
            }
        
            $Rows = \DB::select('CALL CalcConsolidado(?)', array($year));

            foreach($Rows as $r){
                $json_arrays['header_date_rows'][$i]['CONCEPTO'] = $r->Concepto;

                foreach($json_arrays['header_date'] as $dtFecha => $valor){
                    
                    $rows_in = date("M", strtotime($valor)) . ltrim(date("y", strtotime($valor)), '0');

                    $json_arrays['header_date_rows'][$i][$rows_in] =  number_format($r->$rows_in,2)  ;
                    
                }
                $i++;
            }
            

            return $json_arrays;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getIndicadores(Request $request)
    {
        $dtEnd  = $request->input('dtEnd').' 00:00:00';
        $array  = array();
        
        $Indicadores = ConsolidadoCat::WhereIn('id_cat_consolidado',[8,9,12,13,14])->get()->toArray();

        $Obj =  Consolidado::whereMonth('Fecha', '=', date('m', strtotime($dtEnd)))
                            ->whereIn('Concepto', array_column($Indicadores, 'key'))
                            ->get();

        foreach ($Obj as $key => $a) {  
            $array[$key] = [
                "Id"            => $a->id_consolidado,
                "Fecha_gasto"   => \Date::parse($a->Fecha)->format('d-m-Y') ,
                "Concepto"      => $a->ConsolidadoCategoria[0]->descripcion,
                "Monto"         => $a->Valor,
                "Usuario"       =>'',
            ];
                
        }
        return $array;
    }
    public static function SaveIndiador(Request $request)
    {
        if ($request->ajax()) {
            try {

                $existingRecord = Consolidado::where('Concepto', $request->input('_Indicador'))
                    ->where('Fecha', $request->input('_Fecha'))
                    ->exists();

                if ($existingRecord) {
                    $response = Consolidado::where('Concepto', $request->input('_Indicador'))
                    ->where('Fecha', $request->input('_Fecha'))
                    ->update([
                        'Concepto'  => $request->input('_Indicador'),
                        'Fecha'     => $request->input('_Fecha'),
                        'Valor'     => $request->input('_Monto'),
                    ]);
                } else {
                    $response = Consolidado::insert([
                        'Concepto'  => $request->input('_Indicador'),
                        'Fecha'     => $request->input('_Fecha'),
                        'Valor'     => $request->input('_Monto'),
                    ]);
                }

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function RemoveIndicador(Request $request)
    {
        if ($request->ajax()) {
            try {
                $ID         = $request->input('IdIndicador');
                
                $response =   Consolidado::where('id_consolidado',  $ID)->delete();
    
                return $response;
    
    
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function getInfoIndicador(Request $request)
    {
        $ID = $request->input('IdConso');
        $Obj = Consolidado::where('id_consolidado', $ID)->get();
        $array_gasto_ope = array();
        foreach ($Obj as $key => $a) {
            $array_gasto_ope = [
                "Id" => $a->id_consolidado,
                "Fecha" => \Date::parse($a->Fecha)->format('d/m/Y'),
                "Concepto" => $a->Concepto,
                "Monto" => $a->Valor,
            ];
        }
        return $array_gasto_ope;
    }
    public static function ExportConsolidado(Request $request) 
    {

        
        
        $objPHPExcel = new PHPExcel();
        $tituloReporte = "";
        $titulosColumnas = array();

        $SelectYear = $request->input('SelectYear');

        $Consolidado = Consolidado::CalcConsolidado($SelectYear);

        $NameMonth = 'CONSOLIDADO | ' . \Date::parse(date('Y-m-d'))->format('F');
        $NameMonth = mb_strtoupper($NameMonth);

        $num_row    =  20 ;
    
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


        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H3');
        $style = array(
            'font' => array(
            'name'      => 'Tahoma',
            'bold'      => true,
            'italic'    => false,
            'strike'    => false,
            'size'      => 10,
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
        $objPHPExcel->getActiveSheet()->getStyle('A1:H3')->applyFromArray($style);
    
        $color_totales = array(                   
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '92D050') 
            )
        );
        
        $objPHPExcel->setActiveSheetIndex()
        ->setCellValue('A1', "CREDINSTANTE | ". $NameMonth)
        ->setCellValue('A5', "CONCEPTO ") ;
        
        $alphabet = range('B', 'Z');
        foreach ($Consolidado['header_date'] as $k) {
            $column = array_shift($alphabet);
            $objPHPExcel->getActiveSheet()->setCellValue($column.'5', $k);
            
        }

        $objPHPExcel->setActiveSheetIndex()->getStyle('A5:H5')->applyFromArray($color_totales);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        
        $objPHPExcel->getActiveSheet()->getStyle('A5:H5')->applyFromArray($estiloTituloColumnas);      

        $i=6;

        $nameColumn = range('B', 'Z');
        foreach ($Consolidado['header_date_rows'] as $r => $v) {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i,  strtoupper($v['CONCEPTO']));
                foreach ($Consolidado['header_date'] as $k => $vl)  {
                    $column     = $nameColumn[$k];
                    $Valor      = str_replace(',', '', $v[$vl]);
                    $objPHPExcel->getActiveSheet()->setCellValue($column.$i, $Valor);
                }
            $i++;
        }

        $formatCode = '_-"$"* #,##0.00_-;_-"$"* #,##0.00_-;_-"$"* "-"??_-;_-@_-';
        $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A6:H".$num_row);
        $objPHPExcel->getActiveSheet()->getStyle('C6:H6')->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('B6:H'.$num_row)->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('B6:H'.$num_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Consolidado.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        
        
    }
}
