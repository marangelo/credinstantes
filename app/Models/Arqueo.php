<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

class Arqueo extends Model {
    public $timestamps = false;
    protected $table = "tbl_arqueo";
    protected $primaryKey = 'id_arqueo';

    public function getDetalles()
    {
        return $this->hasMany(ArqueoDetalle::class, 'id_arqueo', 'id_arqueo');
    }
    public function getZona()
    {
        return $this->hasOne(Zonas::class, 'id_zona','id_zona');
    }
    public static function UpdateArqueo(Request $request){
        if ($request->ajax()) {
            try {

                $Arqueo     = $request->input('Arqueo');
                $Fecha      = $request->input('Fecha');
                $Deposit    = $request->input('Deposit');
                $Tranfe     = $request->input('Tranfe');
                $Gastos     = $request->input('Gastos');
                $Commit     = $request->input('Commit');

                $response = Arqueo::where('id_arqueo',$Arqueo)->update([
                    "fecha_arqueo"          => $Fecha,
                    "deposito_dia"          => $Deposit,
                    "deposito_tranferencia" => $Tranfe,
                    "gasto_operacion"       => $Gastos,
                    "comentario"            => $Commit
                ]);

                return $response;   
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    } 
    public static function UpdateRowArqueo(Request $request){
        if ($request->ajax()) {
            try {

                $Arqueo     = $request->input('Arqueo');
                $Linea      = $request->input('Linea');
                $Cantidad   = $request->input('Cantidad');
                $Denomi     = $request->input('Denomi');
                $TC         = $request->input('TC');
                $Moneda     = $request->input('Moneda');


                $TC = ($Moneda === 'NIO' ) ? 1 : $TC ;

                $Total   = (floatval($Denomi) * floatval($Cantidad)) * floatval($TC);    

                $response = ArqueoDetalle::where('id_arqueo',  $Arqueo)->where('arqueo_linea',  $Linea)->update([
                    "cantidad"  => $Cantidad,
                    "total"     => $Total
                ]);

                return $response;   
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    } 

    public static function Export($ID) {
        
        $objPHPExcel = new PHPExcel();
        $tituloReporte = "";
        $titulosColumnas = array();

        $Arqueo     = Arqueo::find($ID);
        $ttSistema  = 33000;

        

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

            $right = array(
                'alignment' =>  array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap' => TRUE
                )
            );


          

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D3');
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
                $objPHPExcel->getActiveSheet()->getStyle('A1:D3')->applyFromArray($style);

                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', "CREDINSTANTE ARQUEO DE CAJA MARTES 19 DE MARZO ")
                ->setCellValue('A5',  'ZONA/RUTA')
                ->setCellValue('B5',  'SISTEMA')
                ->setCellValue('C5',  number_format($ttSistema,0,'.',''))
                ->setCellValue('D5',  '')
                ->setCellValue('A6',  'ARQ #'.$Arqueo->id_arqueo.' '. strtoupper ( $Arqueo->getZona->nombre_zona ).' / ESTA PENDIENTE EL NOMBRE')
                ->setCellValue('B6',  'DENOMINACION')
                ->setCellValue('C6',  'CANTIDAD')
                ->setCellValue('D6',  'TOTAL');

                
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                
                $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->applyFromArray($estiloTituloColumnas);  
                $objPHPExcel->getActiveSheet()->getStyle('A6:D6')->applyFromArray($estiloTituloColumnas);  


                

                $i=7;
                $ttNIO = 0;
                foreach ($Arqueo->getDetalles as $a ){
                    if($a->moneda === 'NIO'){  
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.$i,  '')
                                    ->setCellValue('B'.$i,  $a->denominacion)
                                    ->setCellValue('C'.$i,  $a->cantidad)
                                    ->setCellValue('D'.$i,  $a->total);
                                    $ttNIO += $a->total;
                                    $i++;
                    }
                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  'SUB TOTAL CORDOBAS')
                ->setCellValue('B'.$i,  '-')
                ->setCellValue('C'.$i,  '-')
                ->setCellValue('D'.$i,  number_format($ttNIO,0,'.',''));
                $i++;

                $ttUSD = 0 ;
                foreach ($Arqueo->getDetalles as $a ){
                    if($a->moneda === 'USD'){  
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A'.$i,  '')
                                    ->setCellValue('B'.$i,  $a->denominacion)
                                    ->setCellValue('C'.$i,  $a->cantidad)
                                    ->setCellValue('D'.$i,  $a->total);
                                    $ttUSD += $a->total;
                                    $i++;
                    }
                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  'SUB TOTAL DOLARES - CORDOBAS')
                ->setCellValue('B'.$i,  '-')
                ->setCellValue('C'.$i,  '-')
                ->setCellValue('D'.$i,  number_format($ttUSD,0,'.',''));
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  'DESEMBOLSOS DEL DIA ')
                ->setCellValue('B'.$i,  '-')
                ->setCellValue('C'.$i,  '-')
                ->setCellValue('D'.$i,  number_format($Arqueo->deposito_dia,0,'.',''));
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  'DEPOSITOS O TRANSFERENCIAS')
                ->setCellValue('B'.$i,  '-')
                ->setCellValue('C'.$i,  '-')
                ->setCellValue('D'.$i,  number_format($Arqueo->deposito_tranferencia,0,'.',''));
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  'GASTO OPERATIVO DEL DIA')
                ->setCellValue('B'.$i,  '-')
                ->setCellValue('C'.$i,  '-')
                ->setCellValue('D'.$i,  number_format($Arqueo->gasto_operacion,0,'.',''));
                $i++;

                $ttTotal = $ttNIO + $ttUSD + $Arqueo->deposito_dia + $Arqueo->deposito_tranferencia + $Arqueo->gasto_operacion ;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  'TOTAL')
                ->setCellValue('B'.$i,  '-')
                ->setCellValue('C'.$i,  '-')
                ->setCellValue('D'.$i,  number_format($ttTotal,0,'.',''));
                $i++;

                $ttTotal_Final =  $ttTotal - $ttSistema;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  'CUADRADO SEGÚN SISTEMA CONTRA EFECTIVO')
                ->setCellValue('B'.$i,  '-')
                ->setCellValue('C'.$i,  '-')
                ->setCellValue('D'.$i,  number_format($ttTotal_Final,0,'.',''));
                
                $objPHPExcel->getActiveSheet()->mergeCells('A31:B31');
                $i = $i + 2 ; 


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A34',  'COMENTARIO:');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A35',  $Arqueo->comentario); 
                $objPHPExcel->getActiveSheet()->mergeCells('A35:D36');
                $style = array(
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
                $objPHPExcel->getActiveSheet()->getStyle('A35:D36')->applyFromArray($style);
                


                $f = 39; 



                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$f,  '_____________________________________');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$f,  '_____________________________________');
                $objPHPExcel->getActiveSheet()->mergeCells('C'.$f.':D'.$f);                 
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$f.':B'.$f);

                $f++;
                
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$f,  'FIRMA DEL GESTOR');
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$f.':B'.$f);                
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$f,  'FIRMA OPERACIONES');
                $objPHPExcel->getActiveSheet()->mergeCells('C'.$f.':D'.$f); 


                $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A7:D31");
                //$objPHPExcel->getActiveSheet()->getStyle("B7:D".($i-1))->applyFromArray($right);
                $formatCode = '_-"$"* #,##0.00_-;_-"$"* #,##0.00_-;_-"$"* "-"??_-;_-@_-';
                $objPHPExcel->getActiveSheet()->getStyle('C5:D5')->getNumberFormat()->setFormatCode($formatCode);
                $objPHPExcel->getActiveSheet()->getStyle('B7:D31')->getNumberFormat()->setFormatCode($formatCode);
                $objPHPExcel->getActiveSheet()->getStyle('B7:D31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);




                //COLORES DE LOS TOTALES
                $color_totales = array(                   
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '00B050') 
                    )
                );
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('B5')->applyFromArray($color_totales);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('D18')->applyFromArray($color_totales);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('D26')->applyFromArray($color_totales);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('D28')->applyFromArray($color_totales);

                 //COLORES DE LOS TOTALES
                $color_totales = array(                   
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '92D050') 
                    )
                );
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A5')->applyFromArray($color_totales);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A18')->applyFromArray($color_totales);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A26')->applyFromArray($color_totales);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A28')->applyFromArray($color_totales);

                $color_totales = array(                   
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '7030A0') 
                    )
                );
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A29')->applyFromArray($color_totales);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('D29')->applyFromArray($color_totales);


                
                $color_totales = array(                   
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FFFF00') 
                    )
                );
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A30')->applyFromArray($color_totales);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('D30')->applyFromArray($color_totales);

                $color_totales = array(                   
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'ED7D31') 
                    )
                );
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('D31')->applyFromArray($color_totales);

                $color_totales = array(                   
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'F8CBAD') 
                    )
                );
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A6:D6')->applyFromArray($color_totales);

                


                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C5:D5');
                $style_center = array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FFFF00') 
                    )
                );

                $objPHPExcel->getActiveSheet()->getStyle('C5:D5')->applyFromArray($style_center);
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A31:B31');

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7',  'BILLETES CORDOBAS');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A14',  'MONEDAS CORDOBAS');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A19',  'DOLARES');

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Arqueos.xlsx"');
                header('Cache-Control: max-age=0');
        
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
        
        
    }

    public static function DataTableMoneda(Request $request)
    {
        $Moneda    = $request->input('Moneda');
        $IdArqeo   = $request->input('Id');

        $array_moneda = array();

        $moneda_linea = 0;
        
        $Arqueo = Arqueo::find($IdArqeo);

        foreach ($Arqueo->getDetalles as $a ){
            if($a->moneda === $Moneda){            
                $array_moneda[] = [
                    "Id"            => $a->id_arqueo,
                    "Linea"         => $a->arqueo_linea,
                    "denominacion"  => $a->denominacion,
                    "cantidad"      => $a->cantidad,
                    "total"         => $a->total,
                ];
                $moneda_linea++;
            }
        }
        return $array_moneda;
    }

    public static function getDataArqueos(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';
        $IdZna    = $request->input('IdZna');

        $Obj =  Arqueo::whereBetween('fecha_arqueo', [$dtIni, $dtEnd])->Where('activo',1);

        if ($IdZna > 0) {
            $Obj->Where('id_zona',$IdZna);
        }

        $Arqueos = $Obj->get();

        $array_arqueos = array();
        
        foreach ($Arqueos as $key => $a) {  
            
            $array_arqueos[$key] = [
                "Id"                        => $a->id_arqueo,
                "Fecha_Cuota"               => $a->fecha_arqueo,
                "Zona"                      => $a->id_zona,
                // "Nombre"            => strtoupper($a->credito->Clientes->nombre),
                // "apellido"          => strtoupper($a->credito->Clientes->apellidos),
                "cuota_cobrada"             => $a->deposito_dia,
                "deposito_tranferencia"     => $a->deposito_tranferencia,
                "gasto_operacion"           => $a->gasto_operacion,
            ];
                
        }


        return $array_arqueos;
    }


    public static function InitArqueo($IdZona)
    {
        try {
            

            $MONEDA_NIO = [1000.00,500.00,200.00,100.00,50.00,20.00,10.00,5.00,1.00,0.50,0.25];
            $MONEDA_USD = [100.00,50.00,20.00,10.00,5.00,2.00,1.00];
            $ARQUEO_DET = [];

            $Linea = 1;

            

            $datos_a_insertar = [
                'fecha_arqueo'              => date('Y-m-d'),
                'id_zona'                   => $IdZona,
                'tc'                        => 36.00,
                'deposito_dia'              => 0.00,
                'deposito_tranferencia'     => 0.00,
                'gasto_operacion'           => 0.00,
                'activo'                    => 1
            ];

            $IdInsertado = Arqueo::insertGetId($datos_a_insertar);

            foreach ($MONEDA_NIO as $m) {
                $ARQUEO_DET[$Linea] = [
                    'id_arqueo'       => $IdInsertado, 
                    'arqueo_linea'    => $Linea,
                    'denominacion'    => $m,                    
                    'cantidad'        => 0,
                    'total'           => 0,
                    'Moneda'          => 'NIO'
                ];
                $Linea++;
            }
            foreach ($MONEDA_USD as $m) {
                $ARQUEO_DET[$Linea] = [
                    'id_arqueo'       => $IdInsertado, 
                    'arqueo_linea'    => $Linea,
                    'denominacion'    => $m,                    
                    'cantidad'        => 0,
                    'total'           => 0,
                    'Moneda'          => 'USD'
                ];
                $Linea++;
            }

            ArqueoDetalle::insert($ARQUEO_DET);

            $array = [
                "ID_ARQUEO"       => $IdInsertado,
                "FECHA_ARQUEO"    => date('Y-m-d'),
            ];
            return $array;
        
        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
            return response()->json($mensaje);
        }

    }

}