<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;


class Payroll extends Model {
    protected $table = "tbl_payrolls";
    protected $connection = 'mysql';
    protected $primaryKey = 'id_payrolls';
    public function Status()
    {
        return $this->belongsTo(PayrollStatus::class, 'payroll_status_id','id_payroll_status');
    }
    public function Type()
    {
        return $this->belongsTo(PayrollType::class, 'payroll_type_id','id_payroll_type');
    }
    public function PayrollEmploye()
    {
        return $this->hasMany(PayrollEmploye::class, 'payrolls_id','payroll_type_id');
        
    }
    public function PayrollDetails()
    {
        return $this->hasMany(Payroll_details::class, 'payroll_id', 'id_payrolls');
    }
    public static function setNamePayroll(Request $request)
    {
        $dtIni = $request->payroll_date_ini_;

        return date('d', strtotime($dtIni)) <= 15 ? "1Q-" . date('M-y', strtotime($dtIni)) : "2Q-" . date('M-y', strtotime($dtIni));
    }
    public static function setPayrollDetails($Id_payroll)
    {
        $Payrolls = Payroll::where('id_payrolls',$Id_payroll)->first();

        $Key = 0 ;

        $Employes = $Payrolls->PayrollEmploye;

        if ($Payrolls->payroll_type_id == 1 || $Payrolls->payroll_type_id == 3) {

            foreach ($Employes as $p) {
                if(isset($p->Employee->first_name)){        
                    $datos_a_insertar[$Key] = [
                        'payroll_id'            => $Id_payroll,
                        'employee_full_name'    => $p->Employee->first_name . " " . $p->Employee->last_name,
                        'employee_position'     => '',
                        'cedula'                => $p->Employee->cedula_number,
                        'inns'                  => $p->Employee->inss_number,
                        'mes'                   => \Date::parse(date('Y-m-d'))->format('F'),
                        'fecha'                 => date('Y-m-d H:i:s'),
                        'salario_mensual'       => $p->Employee->salario_mensual,
                        'dias_trabajados'       => 0,
                        'salario_quincenal'     => 0,
                        'comision'              => 0,
                        'neto_pagar'            => 0,
                        'vacaciones'            => 0,
                        'aguinaldo'             => 0,
                        'indenmnizacion'        => 0,
                    ];
    
                    $Key++;
                }
            }
        } else{

            foreach ($Employes as $p) {
                if(isset($p->Employee->first_name)){
                    $NetoPagar  = 0.00;
                    $datos_a_insertar[$Key] = [
                        'payroll_id'            => $Id_payroll,
                        'employee_full_name'    => $p->Employee->first_name . " " . $p->Employee->last_name,
                        'employee_position'     => '',
                        'cedula'                => $p->Employee->cedula_number,
                        'inns'                  => $p->Employee->inss_number,
                        'mes'                   => \Date::parse(date('Y-m-d'))->format('F'),
                        'fecha'                 => date('Y-m-d H:i:s'),
                        'neto_pagar'            => $NetoPagar,
                        'concepto'            => ' ',
                    ];
    
                    $Key++;
                }
            }

        }

        

        Payroll_details::where('payroll_id', $Id_payroll)->delete();
        Payroll_details::insert($datos_a_insertar);
    }
    public static function SavePayroll(Request $request)
    {
            try {

                DB::transaction(function () use ($request) {

                    $idInsertado = Payroll::insertGetId([
                        'company_id'            => 0,
                        'payroll_type_id'       => $request->payroll_type_,
                        'payroll_status_id'     => 1,
                        'payroll_name'          => self::setNamePayroll($request),
                        'payroll_date'          => $request->payroll_date_,
                        'start_date'            => $request->payroll_date_ini_,
                        'end_date'              => $request->payroll_date_end_,
                        'inss_patronal'         => $request->payroll_inss_patronal_,
                        'Inatec'                => $request->payroll_inactec_,
                        'observation'           => $request->payroll_observation_,
                        'user_id'               => Auth::User()->id,
                        'active'                => 1,
                        'updated_at'            => date('Y-m-d H:i:s'),
                        'created_at'            => date('Y-m-d H:i:s'),
                    ]);

                    self::setPayrollDetails($idInsertado);
                });
                
                
                
            } catch (Exception $e) {
                dd($e->getMessage());
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        
    }
    public static function RemovePayroll(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Id           = $request->input('id_');
                $response =  Payroll::where('id_payrolls',  $Id)->update([
                    "active" => 0,
                ]);
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function ProcessPayroll(Request $request)
    {
        if ($request->ajax()) {
            try {
                $IdPayRoll      = $request->input('idPayroll_');
                $PayRoll_status = $request->input('Type_PayRoll_');

                $PayRoll_status = $PayRoll_status + 1 ;

                $NetoPagado = number_format(str_replace(',', '', $request->input('neto_pagar_payroll_')),2,'.','');

                $dateRecord = date('Y-m-d H:i:s');
                
                $response =  Payroll::where('id_payrolls',  $IdPayRoll)->update([
                    "neto_pagado"       => $NetoPagado,
                    "payroll_status_id" => $PayRoll_status,
                    "updated_at"        => $dateRecord,
                ]);


                //GENERAR EL PAGO DE NOMINA DENTRO DE LOS GASTOS DE OPERACION
                if ($PayRoll_status == '3') {

                    $Payrolls = Payroll::where('id_payrolls',$IdPayRoll)->first();
                    $NamePayroll = $Payrolls->payroll_name;
                    $NameType    =   $Payrolls->Type->payroll_type_name;

                    $response = GastosOperaciones::insert([
                        'concepto'      => "PAGO DE ".$NameType.": ".$NamePayroll,
                        'fecha_gasto'   => $dateRecord,
                        'monto'         => $NetoPagado,
                        'id_user'       => Auth::id(),
                        'activo'        => 1,
                    ]); 
                }
                
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function EmployeeTypePayroll(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Employee       = $request->Employee_;
                $PayrollType    = $request->PayrollType_;
                $isChecked      = $request->isChecked_;
            

                if ($isChecked === "true") {
                    $p = new PayrollEmploye();
                    $p->employee_id     = $Employee;
                    $p->payrolls_id     = $PayrollType;
                    $p->save();
                } else {
                    PayrollEmploye::where('employee_id',  $Employee)->where('payrolls_id',  $PayrollType)->delete();
                }
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function ExportPayrollQuincenal(Request $request) 
    {
        
        $objPHPExcel = new PHPExcel();
        $tituloReporte = "";
        $titulosColumnas = array();

        $id_Payroll = $request->input('id_Payroll');


        $Payrolls    = Payroll::Where('id_payrolls', $id_Payroll)->first();
        $Employes   = $Payrolls->PayrollDetails;
        $NameMonth  = strtoupper($Payrolls->Type->payroll_type_name) . " | " .$Payrolls->payroll_name;


        $num_row    =  $Employes->count() + 6;
    
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


        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:M3');
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
        $objPHPExcel->getActiveSheet()->getStyle('A1:M3')->applyFromArray($style);
    
        $color_totales = array(                   
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '92D050') 
            )
        );
        
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', "CREDINSTANTE | ". $NameMonth) 
        ->setCellValue('A5',  'NOMBRES Y APELLIDOS')
        ->setCellValue('B5',  'MES        ')
        ->setCellValue('C5',  'FECHA')
        ->setCellValue('D5',  'CEDULA')
        ->setCellValue('E5',  'INSS')
        ->setCellValue('F5',  'SALARIO MENSUAL')
        ->setCellValue('G5',  'DIAS TRABAJADOS')
        ->setCellValue('H5',  'SALARIO QUINCENAL')
        ->setCellValue('I5',  'NETO A PAGAR')
        ->setCellValue('J5',  'FIRMA')
        ->setCellValue('K5',  'VACACIONES')
        ->setCellValue('L5',  'PROV. AGUINALDO')
        ->setCellValue('M5',  'PROV. INDENMNIZACION');

        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A5:M5')->applyFromArray($color_totales);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        
        $objPHPExcel->getActiveSheet()->getStyle('A5:M5')->applyFromArray($estiloTituloColumnas);      

        $i=6;
        
        $ttVACACIONES = 0;
        $ttAGUINALDO = 0;
        $ttINDENMNIZACION = 0;
        $ttNetoPagar = 0;   

        foreach ($Employes as $e ){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $e->employee_full_name)
            ->setCellValue('B'.$i,  strtoupper($e->mes))
            ->setCellValue('C'.$i,  \Date::parse($e->fecha)->format('d/m/Y'))
            ->setCellValue('D'.$i,  $e->cedula)
            ->setCellValue('E'.$i,  $e->inns)
            ->setCellValue('F'.$i,  $e->salario_mensual)
            ->setCellValue('G'.$i,  $e->dias_trabajados)
            ->setCellValue('H'.$i,  $e->salario_quincenal)
            ->setCellValue('I'.$i,  $e->neto_pagar)
            ->setCellValue('J'.$i,  ' ')
            ->setCellValue('K'.$i,  $e->vacaciones)
            ->setCellValue('L'.$i,  $e->aguinaldo)
            ->setCellValue('M'.$i,  $e->indenmnizacion);

            $ttVACACIONES += $e->vacaciones;
            $ttAGUINALDO += $e->aguinaldo;
            $ttINDENMNIZACION += $e->indenmnizacion;
            $ttNetoPagar += $e->neto_pagar;

            $i++;
            
        }

        $formatCode = '_-"$"* #,##0.00_-;_-"$"* #,##0.00_-;_-"$"* "-"??_-;_-@_-';
        $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  '')
                ->setCellValue('A'.$i,  'SUBTOTAL')
                ->setCellValue('I'.$i,  number_format($ttNetoPagar,2,'.',''))
                ->setCellValue('K'.$i,  number_format($ttVACACIONES,2,'.',''))
                ->setCellValue('L'.$i,  number_format($ttAGUINALDO,2,'.',''))
                ->setCellValue('M'.$i,  number_format($ttINDENMNIZACION,2,'.',''));
                
        
        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A6:M".$num_row);
        $objPHPExcel->getActiveSheet()->getStyle('C6:M6')->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('C6:M6')->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('B6:M'.$num_row)->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('B6:M'.$num_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="NominaQuincenal.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        
        
    }
    public static function ExportPayrollDepreciacion(Request $request) 
    {
        
        $objPHPExcel = new PHPExcel();
        $tituloReporte = "";
        $titulosColumnas = array();

        $id_Payroll = $request->input('id_Payroll');


        $Payrolls   = Payroll::Where('id_payrolls', $id_Payroll)->first();
        $Employes   = $Payrolls->PayrollDetails;
        $NameMonth  = strtoupper($Payrolls->Type->payroll_type_name) . " | " .$Payrolls->payroll_name;

        $num_row    =  $Employes->count() + 6;
    
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


        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E3');
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
        $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->applyFromArray($style);
    
        $color_totales = array(                   
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '92D050') 
            )
        );
        
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', "CREDINSTANTE | ". $NameMonth) 
        ->setCellValue('A5',  'NOMBRES Y APELLIDOS')
        ->setCellValue('B5',  'MES        ')
        ->setCellValue('C5',  'FECHA')
        ->setCellValue('D5',  'CONCEPTO')
        ->setCellValue('E5',  'PAGO');

        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A5:E5')->applyFromArray($color_totales);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        
        $objPHPExcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($estiloTituloColumnas);      

        $i=6;
        
        $ttNetoPagar = 0;

        foreach ($Employes as $e ){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $e->employee_full_name)
            ->setCellValue('B'.$i,  strtoupper($e->mes))
            ->setCellValue('C'.$i,  \Date::parse($e->fecha)->format('d/m/Y'))
            ->setCellValue('D'.$i,  $e->concepto)
            ->setCellValue('E'.$i,  $e->neto_pagar);

            $ttNetoPagar += $e->neto_pagar;

            $i++;
            
        }

        $formatCode = '_-"$"* #,##0.00_-;_-"$"* #,##0.00_-;_-"$"* "-"??_-;_-@_-';
        $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  '')
                ->setCellValue('A'.$i,  'SUBTOTAL')
                ->setCellValue('E'.$i,  number_format($ttNetoPagar,2,'.',''));
                
        
        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A6:E".$num_row);
        $objPHPExcel->getActiveSheet()->getStyle('C6:E6')->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('C6:E6')->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('B6:E'.$num_row)->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('B6:E'.$num_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="NominaDepreciacion.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        
        
    }
    public static function ExportPayrollComisiones(Request $request) 
    {
        
        $objPHPExcel = new PHPExcel();
        $tituloReporte = "";
        $titulosColumnas = array();

        $id_Payroll = $request->input('id_Payroll');


        $Payrolls    = Payroll::Where('id_payrolls', $id_Payroll)->first();
        $Employes   = $Payrolls->PayrollDetails;
        $NameMonth  = strtoupper($Payrolls->Type->payroll_type_name) . " | " .$Payrolls->payroll_name;


        $num_row    =  $Employes->count() + 6;
    
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


        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K3');
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
        $objPHPExcel->getActiveSheet()->getStyle('A1:K3')->applyFromArray($style);
    
        $color_totales = array(                   
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '92D050') 
            )
        );
        
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', "CREDINSTANTE | ". $NameMonth) 
        ->setCellValue('A5',  'NOMBRES Y APELLIDOS')
        ->setCellValue('B5',  'MES        ')
        ->setCellValue('C5',  'FECHA')
        ->setCellValue('D5',  'CEDULA')
        ->setCellValue('E5',  'INSS')
        ->setCellValue('F5',  'COMISIONES')
        ->setCellValue('G5',  'NETO A PAGAR')
        ->setCellValue('H5',  'FIRMA')
        ->setCellValue('I5',  'VACACIONES')
        ->setCellValue('J5',  'PROV. AGUINALDO')
        ->setCellValue('K5',  'PROV. INDENMNIZACION');

        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A5:K5')->applyFromArray($color_totales);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        
        $objPHPExcel->getActiveSheet()->getStyle('A5:K5')->applyFromArray($estiloTituloColumnas);      

        $i=6;
        
        $ttVACACIONES = 0;
        $ttAGUINALDO = 0;
        $ttINDENMNIZACION = 0;
        $ttNetoPagar = 0;   

        foreach ($Employes as $e ){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $e->employee_full_name)
            ->setCellValue('B'.$i,  strtoupper($e->mes))
            ->setCellValue('C'.$i,  \Date::parse($e->fecha)->format('d/m/Y'))
            ->setCellValue('D'.$i,  $e->cedula)
            ->setCellValue('E'.$i,  $e->inns)
            ->setCellValue('F'.$i,  $e->comision)
            ->setCellValue('G'.$i,  $e->neto_pagar)
            ->setCellValue('H'.$i,  ' ')
            ->setCellValue('I'.$i,  $e->vacaciones)
            ->setCellValue('J'.$i,  $e->aguinaldo)
            ->setCellValue('K'.$i,  $e->indenmnizacion);

            $ttVACACIONES += $e->vacaciones;
            $ttAGUINALDO += $e->aguinaldo;
            $ttINDENMNIZACION += $e->indenmnizacion;
            $ttNetoPagar += $e->neto_pagar;

            $i++;
            
        }

        $formatCode = '_-"$"* #,##0.00_-;_-"$"* #,##0.00_-;_-"$"* "-"??_-;_-@_-';
        $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,  '')
                ->setCellValue('A'.$i,  'SUBTOTAL')
                ->setCellValue('G'.$i,  number_format($ttNetoPagar,2,'.',''))
                ->setCellValue('I'.$i,  number_format($ttVACACIONES,2,'.',''))
                ->setCellValue('J'.$i,  number_format($ttAGUINALDO,2,'.',''))
                ->setCellValue('K'.$i,  number_format($ttINDENMNIZACION,2,'.',''));
                
        
        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A6:K".$num_row);
        $objPHPExcel->getActiveSheet()->getStyle('C6:K6')->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('C6:K6')->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('B6:K'.$num_row)->getNumberFormat()->setFormatCode($formatCode);
        $objPHPExcel->getActiveSheet()->getStyle('B6:K'.$num_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="NominaComisiones.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        
        
    }
}
