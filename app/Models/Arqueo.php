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

use Auth;

class Arqueo extends Model {
    public $timestamps = false;
    protected $table = "tbl_arqueo";
    protected $primaryKey = 'id_arqueo';

    public function getDetalles()
    {
        return $this->hasMany(ArqueoDetalle::class, 'id_arqueo', 'id_arqueo');
    }
    public function Desembolso()
    {
        return $this->hasMany(ArqueoDesembolso::class, 'id_arqueo', 'id_arqueo');
    }
    public function Transferencia()
    {
        return $this->hasMany(ArqueoTransferencia::class, 'id_arqueo', 'id_arqueo');
    }
    public function getZona()
    {
        return $this->hasOne(Zonas::class, 'id_zona','id_zona');
    }

    public function getUser()
    {
        return $this->hasOne(Usuario::class, 'id','created_by');
    }

    public static function UpdateArqueo(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Arqueo     = $request->input('Arqueo');
                $Fecha      = $request->input('Fecha');
                $Deposit    = $request->input('Deposit');
                $Tranfe     = $request->input('Tranfe');
                $Gastos     = $request->input('Gastos');
                $Commit     = $request->input('Commit');
                $ttSYS      = $request->input('ttSYS');
                $Gastos     = $request->input('Gastos');

                $response = Arqueo::where('id_arqueo',$Arqueo)->update([
                    "fecha_arqueo"          => $Fecha,
                    "deposito_dia"          => $Deposit,
                    "deposito_tranferencia" => $Tranfe,                    
                    "comentario"            => $Commit,
                    "Sistema"               => $ttSYS,
                    "gasto_operacion"       => $Gastos
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Arqueo guardado correctamente',
                    'data'    => [
                        'id' => $response
                    ]
                ], 200);
                
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar ',
                    'error'   => $e->getMessage()
                ], 500);
            
            }
        }
    } 
    public static function UpdateRowArqueo(Request $request)
    {
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
    /**
     * Exporta el arqueo de caja a un archivo Excel
     * 
     * @param int $ID ID del arqueo a exportar
     * @return void Genera un archivo Excel y lo envía al navegador
     */
    public static function Export($ID) 
    {
        // Inicializar objeto PHPExcel
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        
        // Obtener datos del arqueo
        $Arqueo = Arqueo::find($ID);
        $ttSistema = $Arqueo->Sistema;
        $Desembolso = $Arqueo->Desembolso;
        $Tranferencia = $Arqueo->Transferencia;
        $name_user_arqueo = (empty($Arqueo->getZona->UsuarioCobrador->nombre)) ? 'N/D' : $Arqueo->getZona->UsuarioCobrador->nombre;
        $name_user_creator = (empty($Arqueo->getUser->nombre)) ? 'N/D' : $Arqueo->getUser->nombre  ;
    
        // ========== DEFINICIÓN DE ESTILOS ==========
        $estiloTituloColumnas = array(
            'font' => array(
                'name'  => 'Arial',
                'bold'  => true,
                'size'  => 10,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'       => TRUE
            ),
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            )
        );
        
        $estiloInformacion = new PHPExcel_Style();
        $estiloInformacion->applyFromArray(array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            )
        ));

        $estiloTituloPrincipal = array(
            'font' => array(
                'name'  => 'Tahoma',
                'bold'  => true,
                'size'  => 12,
                'color' => array('rgb' => 'FFFFFF')
            ),
            'fill' => array(
                'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '4472C4') 
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            )
        );

        $estiloCentrado = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        // ========== CONFIGURACIÓN DE COLUMNAS ==========
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);

        // ========== ENCABEZADO PRINCIPAL ==========
        $sheet->mergeCells('A1:D2');
        $sheet->getStyle('A1:D2')->applyFromArray($estiloTituloPrincipal);
        $sheet->setCellValue('A1', "CREDINSTANTE ARQUEO DE CAJA " . strtoupper(\Date::parse($Arqueo->fecha_arqueo)->format('d F')));

        // ========== INFORMACIÓN DE ZONA Y ENCABEZADOS ==========
        $sheet->setCellValue('A5', 'ZONA/RUTA')
                ->setCellValue('B5', 'SISTEMA')
                ->setCellValue('C5', number_format($ttSistema, 0, '.', ''))
                ->setCellValue('A6', 'ARQ #' . $Arqueo->id_arqueo . ' ' . strtoupper($Arqueo->getZona->nombre_zona) . ' / ' . strtoupper($name_user_arqueo))
                ->setCellValue('B6', 'DENOMINACION')
                ->setCellValue('C6', 'CANTIDAD')
                ->setCellValue('D6', 'TOTAL');

        $sheet->getStyle('A5:D5')->applyFromArray($estiloTituloColumnas);
        $sheet->getStyle('A6:D6')->applyFromArray($estiloTituloColumnas);

        // ========== SECCIÓN DE DETALLES DE MONEDA ==========
        $i = 7;
        
        // Etiquetas de secciones
        $sheet->setCellValue('A7', 'BILLETES CORDOBAS');
        $sheet->setCellValue('A14', 'MONEDAS CORDOBAS');
        
        // Detalles en Córdobas (NIO)
        $ttNIO = 0;
        foreach ($Arqueo->getDetalles as $detalle) {
            if ($detalle->moneda === 'NIO') {  
                $sheet->setCellValue('A' . $i, '')
                        ->setCellValue('B' . $i, $detalle->denominacion)
                        ->setCellValue('C' . $i, $detalle->cantidad)
                        ->setCellValue('D' . $i, $detalle->total);
                $ttNIO += $detalle->total;
                $i++;
            }
        }

        // Subtotal Córdobas
        $sheet->setCellValue('A' . $i, 'SUB TOTAL CORDOBAS')
                ->setCellValue('B' . $i, '-')
                ->setCellValue('C' . $i, '-')
                ->setCellValue('D' . $i, number_format($ttNIO, 0, '.', ''));
        $i++;

        // Etiqueta de sección Dólares
        $sheet->setCellValue('A19', 'DOLARES');
        
        // Detalles en Dólares (USD)
        $ttUSD = 0;
        foreach ($Arqueo->getDetalles as $detalle) {
            if ($detalle->moneda === 'USD') {  
                $sheet->setCellValue('A' . $i, '')
                        ->setCellValue('B' . $i, $detalle->denominacion)
                        ->setCellValue('C' . $i, $detalle->cantidad)
                        ->setCellValue('D' . $i, $detalle->total);
                $ttUSD += $detalle->total;
                $i++;
            }
        }

        // Subtotal Dólares
        $sheet->setCellValue('A' . $i, 'SUB TOTAL DOLARES - CORDOBAS')
                ->setCellValue('B' . $i, '-')
                ->setCellValue('C' . $i, '-')
                ->setCellValue('D' . $i, number_format($ttUSD, 2, '.', ''));
        $i++;

        // ========== SECCIÓN DE DESEMBOLSOS ==========
        $sheet->setCellValue('A' . $i, 'DESEMBOLSOS DE RECUPERACION ')
                ->setCellValue('B' . $i, '-')
                ->setCellValue('C' . $i, '-')
                ->setCellValue('D' . $i, number_format($Arqueo->deposito_dia, 2, '.', ''));
        $i++;

        foreach ($Desembolso as $desembolso) {
            $FullName = $desembolso->NameCliente;
            $sheet->setCellValue('A' . $i, strtoupper($FullName))
                    ->setCellValue('B' . $i, '')
                    ->setCellValue('C' . $i, '')
                    ->setCellValue('D' . $i, $desembolso->monto);
            $i++;
        }

        // ========== SECCIÓN DE TRANSFERENCIAS ==========
        $IniRowDepposito = $i;
        $EndRowDepposito = $i + $Tranferencia->count();

        $sheet->setCellValue('A' . $i, 'DEPOSITOS O TRANSFERENCIAS')
                ->setCellValue('B' . $i, '-')
                ->setCellValue('C' . $i, '-')
                ->setCellValue('D' . $i, number_format($Arqueo->deposito_tranferencia, 0, '.', ''));
        $i++;

        foreach ($Tranferencia as $transferencia) {
            $Cuenta = $transferencia->BancoCuentas->Banco->banco . ' ' . $transferencia->BancoCuentas->moneda . ' ' . $transferencia->BancoCuentas->cuenta;
            $sheet->setCellValue('A' . $i, $Cuenta)
                    ->setCellValue('B' . $i, '')
                    ->setCellValue('C' . $i, '')
                    ->setCellValue('D' . $i, $transferencia->monto);
            $i++;
        }

        // ========== SECCIÓN DE DEPÓSITOS DE CLIENTES ==========
        $sheet->setCellValue('A' . $i, 'DEPOSTOS DE CLIENTES')
                ->setCellValue('B' . $i, '-')
                ->setCellValue('C' . $i, '-')
                ->setCellValue('D' . $i, number_format($Arqueo->gasto_operacion, 0, '.', ''));
        $i++;

        // ========== TOTALES FINALES ==========
        $ttTotal = $ttNIO + $ttUSD + $Arqueo->deposito_dia + $Arqueo->deposito_tranferencia + $Arqueo->gasto_operacion;

        $sheet->setCellValue('A' . $i, 'TOTAL')
                ->setCellValue('B' . $i, '-')
                ->setCellValue('C' . $i, '-')
                ->setCellValue('D' . $i, number_format($ttTotal, 2, '.', ''));
        $i++;

        $ttTotal_Final = $ttTotal - $ttSistema;
        $LastRow = $i;

        $sheet->setCellValue('A' . $i, 'CUADRADO SEGÚN SISTEMA CONTRA EFECTIVO')
                ->setCellValue('B' . $i, '-')
                ->setCellValue('C' . $i, '-')
                ->setCellValue('D' . $i, number_format($ttTotal_Final, 2, '.', ''));
        $sheet->mergeCells('A' . $LastRow . ':B' . $LastRow);
        $i++;

        // ========== SECCIÓN DE COMENTARIOS ==========
        $NumRowComentario = $i + 2;
        $sheet->setCellValue('A' . $NumRowComentario, 'COMENTARIO:');
        $NumRowComentario++;
        $sheet->setCellValue('A' . $NumRowComentario, $Arqueo->comentario);
        
        $Merced = $NumRowComentario + 1;
        $sheet->mergeCells('A' . $NumRowComentario . ':D' . $Merced);
        $sheet->getStyle('A' . $NumRowComentario . ':D' . $Merced)->applyFromArray(array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            )
        ));

        // ========== SECCIÓN DE FIRMAS ==========
        $f = $Merced + 2;
        
        // Líneas de firma
        $sheet->setCellValue('A' . $f, '_____________________________________')
                ->setCellValue('C' . $f, '_____________________________________');
        $sheet->mergeCells('A' . $f . ':B' . $f);
        $sheet->mergeCells('C' . $f . ':D' . $f);
        $f++;
        
        // Etiquetas de firma
        $sheet->setCellValue('A' . $f, 'FIRMA DEL GESTOR: '. strtoupper($name_user_arqueo))
                ->setCellValue('C' . $f, 'FIRMA OPERACIONES: '. strtoupper($name_user_creator));
        $sheet->mergeCells('A' . $f . ':B' . $f);
        $sheet->mergeCells('C' . $f . ':D' . $f);
        $f += 2;

        // Firma del gerente
        $sheet->setCellValue('A' . $f, '_____________________________________')
                ->mergeCells('A' . $f . ':D' . $f)
                ->getStyle('A' . $f . ':D' . $f)
                ->applyFromArray($estiloCentrado);
        $f++;
        
        $sheet->setCellValue('A' . $f, 'GERENTE: WILBER RAMOS')
                ->mergeCells('A' . $f . ':D' . $f)
                ->getStyle('A' . $f . ':D' . $f)
                ->applyFromArray($estiloCentrado);

        // ========== APLICAR FORMATOS Y ESTILOS ==========
        $formatCode = '_-"C$"* #,##0.00_-;_-"C$"* #,##0.00_-;_-"C$"* "-"??_-;_-@_-';
        
        $sheet->setSharedStyle($estiloInformacion, "A7:D" . $LastRow);
        $sheet->getStyle('C5:D5')->getNumberFormat()->setFormatCode($formatCode);
        $sheet->getStyle('B7:D' . $LastRow)->getNumberFormat()->setFormatCode($formatCode);
        $sheet->getStyle('B7:D' . $LastRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        // ========== APLICAR COLORES ==========
        // Color verde para totales principales
        $colorVerde = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '00B050')));
        $sheet->getStyle('B5')->applyFromArray($colorVerde);
        $sheet->getStyle('D18')->applyFromArray($colorVerde);
        $sheet->getStyle('D26')->applyFromArray($colorVerde);
        $sheet->getStyle('D' . $IniRowDepposito . ':D' . $EndRowDepposito)->applyFromArray($colorVerde);

        // Color verde claro
        $colorVerdeClaro = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '92D050')));
        $sheet->getStyle('A5')->applyFromArray($colorVerdeClaro);
        $sheet->getStyle('A18')->applyFromArray($colorVerdeClaro);
        $sheet->getStyle('A26')->applyFromArray($colorVerdeClaro);
        $sheet->getStyle('A' . $IniRowDepposito . ':A' . $EndRowDepposito)->applyFromArray($colorVerdeClaro);

        // Color morado para depósitos de clientes
        $colorMorado = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '7030A0')));
        $RowColorDeposito = $EndRowDepposito + 1;
        $sheet->getStyle('A' . $RowColorDeposito)->applyFromArray($colorMorado);
        $sheet->getStyle('D' . $RowColorDeposito)->applyFromArray($colorMorado);

        // Color amarillo para total
        $colorAmarillo = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFF00')));
        $RowColorTotal = $RowColorDeposito + 1;
        $sheet->getStyle('A' . $RowColorTotal)->applyFromArray($colorAmarillo);
        $sheet->getStyle('D' . $RowColorTotal)->applyFromArray($colorAmarillo);
        $sheet->mergeCells('C5:D5');
        $sheet->getStyle('C5:D5')->applyFromArray(array_merge($estiloCentrado, $colorAmarillo));

        // Color naranja para total final
        $colorNaranja = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'ED7D31')));
        $RowColorTotalFinal = $RowColorTotal + 1;
        $sheet->getStyle('D' . $RowColorTotalFinal)->applyFromArray($colorNaranja);

        // Color durazno para encabezados
        $colorDurazno = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'F8CBAD')));
        $sheet->getStyle('A6:D6')->applyFromArray($colorDurazno);

        // ========== GENERAR Y DESCARGAR ARCHIVO ==========
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

            $name_user_arqueo = (empty($a->getZona->UsuarioCobrador->nombre)) ? 'N/D' : $a->getZona->UsuarioCobrador->nombre ;

            $array_arqueos[$key] = [
                "Id"                        => $a->id_arqueo,
                "Fecha_Cuota"               => \Date::parse($a->fecha_arqueo)->format('d-m-Y') ,
                "Zona"                      => $a->getZona->nombre_zona ?? 'N/D',
                "Nombre"                    => strtoupper($name_user_arqueo),
                "cuota_cobrada"             => $a->deposito_dia,
                "deposito_tranferencia"     => $a->deposito_tranferencia,
                "gasto_operacion"           => $a->gasto_operacion,
                "Sistema"                   => $a->Sistema,
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
                'activo'                    => 1,
                'created_by'                => Auth::id(),
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

    public static function Cobrado($ID)
    {
        $Arqueo     = Arqueo::find($ID);
        
        $Fecha      = $Arqueo->fecha_arqueo;
        $Zona       = $Arqueo->id_zona;

        $CAPITAL    = Pagos::where('id_zona', $Zona)->whereDate('FECHA_ABONO', '=', $Fecha)->sum('CAPITAL');
        $INTERES    = Pagos::where('id_zona', $Zona)->whereDate('FECHA_ABONO', '=', $Fecha)->sum('INTERES');

        $Cobrado    = $CAPITAL + $INTERES;

        return $Cobrado;

    }

    public static function AjaxCobrado(Request $request)
    {
        $ID         = $request->input('Arqueo');
        $Fecha      = $request->input('Fecha');

        $Arqueo     = Arqueo::find($ID);
        $Zona       = $Arqueo->id_zona;

        $CAPITAL    = Pagos::where('id_zona', $Zona)->whereDate('FECHA_ABONO', '=', $Fecha)->sum('CAPITAL');
        $INTERES    = Pagos::where('id_zona', $Zona)->whereDate('FECHA_ABONO', '=', $Fecha)->sum('INTERES');

        $Cobrado    = $CAPITAL + $INTERES;

        return $Cobrado;

    }
    public static function RemoveArqueo(Request $request)
    {
        if ($request->ajax()) {
            try {
                $ID         = $request->input('Arqueo');
                
                $response =   Arqueo::where('id_arqueo',  $ID)->update([
                    "activo" => 0,
                ]);
    
                return $response;
    
    
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }

    public static function getDesembolso(Request $request)
    {
        $ID              = $request->input('Arqueo');
        $Desembolso      = ArqueoDesembolso::where('id_arqueo', $ID)->get();
        $data            = array();
        $TotalDesembolso = 0;
        
        foreach ($Desembolso as $a) {
            
            $Accion = (Auth::user()->id_rol == 1) ? '<button class="btn btn-sm btn-danger" onclick="removeDesembolso(' . $a->id_desembolsos . ')"><i class="fas fa-trash"></i></button>' : '';

            $data[] = [
                "id"              => $a->id_desembolsos,
                "nombre_cliente"  => strtoupper($a->NameCliente),
                "monto"           => $a->monto,
                "accion"          => $Accion
            ];
            $TotalDesembolso = $TotalDesembolso + $a->monto;
        }
        return response()->json([
            "data" => $data,
            "Total" => $TotalDesembolso
        ]);

    }
    public static function SaveDesembolso(Request $request)
    {
        try {

                $Arqueo         = $request->input('Arqueo');
                $NameCliente    = $request->input('SelectCliente');
                $Monto          = $request->input('Monto');

                $datos_a_insertar = [
                    'id_arqueo'     => $Arqueo,
                    'NameCliente'    => $NameCliente,
                    'monto'         => $Monto,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'created_by'    => Auth::id(),
                ];


                $id = ArqueoDesembolso::insertGetId($datos_a_insertar);

                return response()->json([
                    'success' => true,
                    'message' => 'Desembolso guardado correctamente',
                    'data'    => [
                        'id' => $id
                    ]
                ], 200);
                
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el desembolso',
                    'error'   => $e->getMessage()
                ], 500);
            } 
    }
    public static function DownDesembolso(Request $request)
    {
        try {
            $ID         = $request->input('IdTransaccion');
            
            $response =   ArqueoDesembolso::where('id_desembolsos',  $ID)->delete();

            return $response;


        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
            return response()->json($mensaje);
        }
    }
    public static function getTransferencias(Request $request)
    {
        $ID                 = $request->input('Arqueo');
        $Transferencias     = ArqueoTransferencia::where('id_arqueo', $ID)->get();
        $TotalTransferencia = 0;


        $data  = array();
        foreach ($Transferencias as $a) {
            $Accion = (Auth::user()->id_rol == 1) ? '<button class="btn btn-sm btn-danger" onclick="removeTransferencia(' . $a->id_tranferencia . ')"><i class="fas fa-trash"></i></button>' : '';
            $data[] = [
                "id"        => $a->id_tranferencia,
                "cuenta"    => $a->BancoCuentas->Banco->banco.' '.$a->BancoCuentas->moneda.' '.$a->BancoCuentas->cuenta,
                "monto"     => $a->monto,
                "refe"      => $a->referencia,
                "accion"    => $Accion
            ];
            $TotalTransferencia = $TotalTransferencia + $a->monto;
        }
        return response()->json([
            "data" => $data,
            "Total" => $TotalTransferencia
        ]);

    }
    public static function SaveTransferencia(Request $request)
    {
        try 
        {

                $Arqueo         = $request->input('Arqueo');
                $SelectCuenta   = $request->input('SelectCuenta');
                $Monto          = $request->input('Monto');
                $Referencia     = $request->input('Referencia');

                $datos_a_insertar = [
                    'id_arqueo'         => $Arqueo,
                    'id_cuenta'         => $SelectCuenta,
                    'monto'             => $Monto,
                    'referencia'        => $Referencia,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => Auth::id(),
                ];


                $id = ArqueoTransferencia::insertGetId($datos_a_insertar);
                return response()->json([
                    'success' => true,
                    'message' => 'Transferencia guardado correctamente',
                    'data'    => [
                        'id' => $id
                    ]
                ], 200);

                return $response;   
                
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el transferencia',
                    'error'   => $e->getMessage()
                ], 500);
            }
        
    }
    public static function DownTransferencia(Request $request)
    {
        try {
            $ID         = $request->input('IdTransaccion');
            
            $response =   ArqueoTransferencia::where('id_tranferencia',  $ID)->delete();

            return $response;


        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
            return response()->json($mensaje);
        }
    }
    public static function getDepositos(Request $request)
    {
        $ID             = $request->input('Arqueo');
        $TotalDeposito  = 0;
        $data           = array();

        $StrDepositos   = "";

        $Depositos = ArqueoDeposito::where('id_arqueo', $ID)->get();
        

        foreach ($Depositos as $a) {

            $Accion = (Auth::user()->id_rol == 1) ? '<button class="btn btn-sm btn-danger" onclick="removeDeposito(' . $a->id_deposito . ')"><i class="fas fa-trash"></i></button>' : '';

            $data[] = [
                "id"        => $a->id_deposito,
                "FECHA"     => $a->fecha_deposito,
                "nombre_cliente"   =>strtoupper($a->Cliente->nombre . " " . $a->Cliente->apellidos),
                "cuenta_bancaria"    => $a->Cuenta->Banco->banco.' '.$a->Cuenta->moneda.' '.$a->Cuenta->cuenta,
                "monto"     => $a->monto,
                "referencias"      => $a->refe,
                "accion"    => $Accion
            ];

            $TotalDeposito = $TotalDeposito + $a->monto;

            $isMoneda = $a->Cuenta->moneda == 'DOLARES' ? '$. ' : 'C$. ';

            $StrDepositos .= sprintf(
                "%-10s - %-10s - %10s - %-10s - %10s\n", 
                strtoupper($a->Cliente->nombre . " " . $a->Cliente->apellidos),
                $a->Cuenta->cuenta,
                $isMoneda.number_format($a->monto, 2),
                'REF. '.$a->refe,
                date('d/m/Y', strtotime($a->fecha_deposito))
            );
            
        }


        return response()->json([
            "data" => $data,
            "Total" => $TotalDeposito,
            "Comentarios" => $StrDepositos
        ]);
    }
    public static function SaveDeposito(Request $request)
    {
        try 
        {

                $Arqueo         = $request->input('Arqueo');
                $SelectCliente  = $request->input('SelectCliente');
                $SelectCuenta   = $request->input('SelectCuenta');
                $Monto          = $request->input('Monto');
                $Referencia     = $request->input('Referencia');
                $FechaDeposito  = $request->input('FechaDeposito');

                $datos_a_insertar = [
                    'id_arqueo'         => $Arqueo,
                    'id_cliente'        => $SelectCliente,
                    'id_cuenta'         => $SelectCuenta,
                    'monto'             => $Monto,
                    'refe'              => $Referencia,
                    'fecha_deposito'    => $FechaDeposito,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => Auth::id(),
                ];


                $id = ArqueoDeposito::insertGetId($datos_a_insertar);

                return response()->json([
                    'success' => true,
                    'message' => 'Deposito guardado correctamente',
                    'data'    => [
                        'id' => $id
                    ]
                ], 200);

                return $response;   
                
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el Deposito',
                    'error'   => $e->getMessage()
                ], 500);
            }
        
    }
    public static function DownDeposito(Request $request)
    {
        try {
            $ID         = $request->input('IdTransaccion');
            
            $response =   ArqueoDeposito::where('id_deposito',  $ID)->delete();

            return $response;


        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
            return response()->json($mensaje);
        }
    }

}