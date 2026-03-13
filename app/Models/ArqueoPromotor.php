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

class ArqueoPromotor extends Model {
    public $timestamps = false;
    protected $table = "tbl_arqueo_promotor";
    protected $primaryKey = 'id_arqueo_prom';


    public function Creditos()
    {
        $SimpleDate = \Date::parse($this->fecha)->format('Y-m-d');
        return $this->hasMany(Credito::class, 'asignado', 'id_promotor')
            ->whereBetween('fecha_apertura', [
                $SimpleDate.' 00:00:00',
                $SimpleDate.' 23:59:59'
            ]);
    }

    public function getPromotor()
    {
        return $this->hasOne(Usuario::class, 'id','id_promotor');
    }
    public function getOperativo()
    {
        return $this->hasOne(Usuario::class, 'id','created_by');
    }

    public static function getData(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';
        $IdZna    = $request->input('IdZna');

        $Obj =  ArqueoPromotor::whereBetween('fecha', [$dtIni, $dtEnd])->Where('activo',1)->WhereIn('estado_arqueo', [0, 1]);

        if ($IdZna > 0) {
            $Obj->Where('id_promotor',$IdZna);
        }

        $Arqueos = $Obj->get();

        $array_arqueos = array();
        
        foreach ($Arqueos as $key => $a) 
        {  
            $Name_user = $a->getPromotor->nombre;
            $Name_zona = $a->getPromotor->Zona->nombre_zona;

            $array_arqueos[$key] = [
                "Id"                => $a->id_arqueo_prom,
                "fecha_arqueo"      => \Date::parse($a->fecha)->format('d-m-Y') ,
                "Zona"              => strtoupper($Name_zona),
                "Nombre"            => strtoupper($Name_user),
                
                "entregado"         => $a->entregado,
                "desembolsado"      => $a->desembolsado,
                "sobrante"          => $a->sobrante,
                "consolidado"       => $a->consolidado,
            ];
            
        }


        return $array_arqueos;
    }
    public static function InitArqueo($IdZona)
    {
        try {

            $datos_a_insertar = [
                'fecha'              => date('Y-m-d'),
                'id_promotor'                   => $IdZona,                
                'entregado'              => 0.00,
                'desembolsado'     => 0.00,
                'sobrante'           => 0.00,
                'consolidado'        => 0.00,
                'activo'                    => 1,
                'created_at'                => date('Y-m-d H:i:s'),
                'estado_arqueo'            => 0,
                'created_by'                => Auth::id(),
            ];

            $IdInsertado = ArqueoPromotor::insertGetId($datos_a_insertar);

            //ArqueoDetalle::insert($datos_a_insertar);

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

    public static function TableDetalles(Request $request)
    {
        $ID_Arqueo = $request->input('Arqueo');

        $Arqueo = ArqueoPromotor::find($ID_Arqueo);
        $PromotorDetalles = ArqueoPromotorDetalles::where('id_arqueo_prom', $ID_Arqueo)->get();

        $EfectivoDesembolsado = 0;

        $Detalles   = array();
        $Resultado  = array();
        $InfoArqueo = array();

        $RowDetalles = (count($PromotorDetalles) > 0) ? $PromotorDetalles : $Arqueo->Creditos;

        foreach ($RowDetalles as $key => $c) 
        {  

            $NombreCliente  = (count($PromotorDetalles) > 0) ? $c->nombre_cliente : strtoupper($c->Clientes->nombre) . ' ' . strtoupper($c->Clientes->apellidos);
            $NombreZona     = (count($PromotorDetalles) > 0) ? $c->nombre_zona : strtoupper($c->Clientes->getZona->nombre_zona);

            $Detalles[$key] = [
                "cliente"           => $NombreCliente,
                "monto_credito"     => $c->monto_credito,
                "comprobante"       => $c->id_creditos,
                "ruta"              => $NombreZona,
            ];
            $EfectivoDesembolsado += $c->monto_credito;
            
        }
    

        //$EfectivoDesembolsado = (is_null($Arqueo->desembolsado)) ? $EfectivoDesembolsado : ($Arqueo->desembolsado ? $Arqueo->desembolsado : 0 );

        //dd($EfectivoDesembolsado);
        
        $InfoArqueo[] = [
            "id_arqueo_prom"     => $Arqueo->id_arqueo_prom,
            "Promotor"           => strtoupper($Arqueo->getPromotor->nombre) . ' \ ' . strtoupper($Arqueo->getPromotor->Zona->nombre_zona),
            "fecha"              => \Date::parse($Arqueo->fecha)->format('Y-m-d'),
            "entregado"          => (is_null($Arqueo->entregado)) ? 0 : number_format($Arqueo->entregado, 2, '.', '') ,
            "desembolsado"       => (is_null($EfectivoDesembolsado)) ? 0 : number_format($EfectivoDesembolsado, 2, '.', '') ,
            "sobrante"           => (is_null($Arqueo->sobrante)) ? 0 : number_format($Arqueo->sobrante, 2, '.', '') ,
            "consolidado"        => (is_null($Arqueo->consolidado)) ? 0 : number_format($Arqueo->consolidado, 2, '.', '') ,
            "comentario"         => $Arqueo->comentario,
            "id_promotor"        => $Arqueo->id_promotor,
        ];



        $Resultado = [
            'InfoArqueo' => $InfoArqueo,
            'Detalles' => $Detalles,
        ];


        return $Resultado;
    }

    public static function UpdateArqueoPromotor(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Arqueo     = $request->input('Arqueo');
                $Fecha      = $request->input('Fecha');
                $Entregado  = $request->input('Entregado');
                $Desembolso = $request->input('Desembolso');
                $Sobrante   = $request->input('Sobrante');
                $Consolido  = $request->input('Consolido');
                $Commit     = $request->input('Commit');

                $response = ArqueoPromotor::where('id_arqueo_prom',$Arqueo)->update([
                    "fecha"            => $Fecha,
                    "entregado"        => $Entregado,
                    "desembolsado"     => $Desembolso,                    
                    "comentario"       => $Commit,
                    "sobrante"         => $Sobrante,
                    "consolidado"      => $Consolido
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
    public static function SaveArqueoPromotor(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Arqueo     = $request->input('Arqueo');
                $Fecha      = $request->input('Fecha');
                $Entregado  = $request->input('Entregado');
                $Desembolso = $request->input('Desembolso');
                $Sobrante   = $request->input('Sobrante');
                $Consolido  = $request->input('Consolido');
                $Commit     = $request->input('Commit');

                $Detalles   = [];
                

                //ACTUALIZA LOS VALORES DEL ARQUEO PROMOTOR 
                $response = ArqueoPromotor::where('id_arqueo_prom',$Arqueo)->update([
                    "fecha"            => $Fecha,
                    "entregado"        => $Entregado,
                    "desembolsado"     => $Desembolso,                    
                    "comentario"       => $Commit,
                    "sobrante"         => $Sobrante,
                    "consolidado"      => $Consolido
                ]);
                

                $Arqueo = ArqueoPromotor::find($Arqueo);

                //INSERTA LOS DETALLES DE LOS CREDITOS
                foreach ($Arqueo->Creditos as $key => $c) 
                {  
                    $Detalles[$key] = [
                        "id_arqueo_prom"    => $Arqueo->id_arqueo_prom,
                        "nombre_cliente"    => strtoupper($c->Clientes->nombre) . ' ' . strtoupper($c->Clientes->apellidos),
                        "monto_credito"     => $c->monto_credito,
                        "id_creditos"       => $c->id_creditos,
                        "nombre_zona"       => strtoupper($c->Clientes->getZona->nombre_zona),
                        "created_by"        => Auth::id(),
                    ];
                    
                }

                $resultado = ArqueoPromotorDetalles::Insert($Detalles);

                ArqueoPromotor::where('id_arqueo_prom',$Arqueo->id_arqueo_prom,)->update([
                    "estado_arqueo" => 1
                ]);


                return response()->json([
                    'success' => true,
                    'message' => 'Arqueo guardado correctamente',
                    'data'    => [
                        'id' => $resultado
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


    public static function Export($ID)
    {
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $Arqueo = ArqueoPromotor::find($ID);
        
        $Detalles = ArqueoPromotorDetalles::where('id_arqueo_prom', $Arqueo->id_arqueo_prom)->get();

        $Promotor = $Arqueo->getPromotor->nombre ?? 'N/D';
        $Operativo = $Arqueo->getOperativo->nombre ?? 'N/D';

        /* ================= ESTILOS ================= */
        $tituloPrincipal = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '00A3E0']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'borders' => ['allborders' => ['style' => 'thin']]
        ];

        $NumAlineado = [
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT]
        ];

        $tituloSecundario = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ];

        $encabezadoTabla = [
            'font' => [
                'bold' => true
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

        $borde = [
            'borders' => ['allborders' => ['style' => 'thin']]
        ];

        /* ================= COLUMNAS ================= */
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(17);
        $sheet->getColumnDimension('C')->setWidth(17);
        $sheet->getColumnDimension('D')->setWidth(17);
        $sheet->getColumnDimension('E')->setWidth(17);
        $sheet->getColumnDimension('F')->setWidth(17);
        $sheet->getColumnDimension('G')->setWidth(15);

        /* ================= ENCABEZADO ================= */
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'ARQUEO DE PROMOTORA');

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', strtoupper($Promotor).'   FECHA: '.\Date::parse($Arqueo->fecha_arqueo)->format('d/m/Y'));
        $sheet->getStyle('A1:G2')->applyFromArray($tituloSecundario);

        /* ================= TITULO ================= */
        $sheet->mergeCells('A4:G5');
        $sheet->setCellValue('A4', 'CREDINSTANTE ARQUEO DE PROMOTORIA '.strtoupper(\Date::parse($Arqueo->fecha_arqueo)->format('l d \\d\\e F Y')));
        $sheet->getStyle('A4:G5')->applyFromArray($tituloPrincipal);

        /* ================= ENCABEZADOS TABLA ================= */
        $row = 6;
        $sheet->fromArray([
            'EFECTIVO ENTREGADO',
            'CLIENTES DESEMBOLSADOS',
            'MONTO DESEMBOLSADO',
            'N° DE COMPROBANTE',
            'RUTA',
            'SOBRANTE',
            'TOTALES SALDOS CONCILIADOS'
        ], null, 'A'.$row);

        $sheet->getStyle('A6:G6')->applyFromArray($encabezadoTabla);

        /* ================= DETALLE ================= */
        $row++;
        $totalDesembolsado = 0;
        $RowInit = $row;

        foreach ($Detalles as $Detalle) {
            $sheet
                ->setCellValue('B'.$row, $Detalle->nombre_cliente)
                ->setCellValue('C'.$row, 'C$ '.number_format($Detalle->monto_credito, 2, '.', ','))
                ->setCellValue('D'.$row, number_format($Detalle->id_creditos, 0, '.', '0'))
                ->setCellValue('E'.$row, $Detalle->nombre_zona);
            $totalDesembolsado += $Detalle->monto_credito;
            $row++;
        }


        



        /* ================= SUBTOTALES ================= */
        $sheet->setCellValue('A'.$row, 'SUB TOTALES')
            ->setCellValue('A'.$RowInit, 'C$ '.number_format($Arqueo->entregado, 2, '.', ','))
            ->setCellValue('C'.$row, 'C$ '.number_format($totalDesembolsado, 2, '.', ','))
            ->setCellValue('F'.$row, 'C$ '.number_format($Arqueo->sobrante, 2, '.', ','))
            ->setCellValue('G'.$row, 'C$ '.number_format($Arqueo->consolidado, 2, '.', ','));

        /* ================= FORMATOS NUMERICOS ================= */
        $sheet->getStyle('A'.$RowInit)->applyFromArray($NumAlineado);
        $sheet->getStyle('C'.$RowInit.':C'.$row)->applyFromArray($NumAlineado);
        $sheet->getStyle('F'.$RowInit.':F'.$row)->applyFromArray($NumAlineado);
        $sheet->getStyle('G'.$RowInit.':G'.$row)->applyFromArray($NumAlineado);
        $sheet->getStyle('A6:G'.$row)->applyFromArray($borde);

        /* ================= COMENTARIOS ================= */
        $row += 3;
        $sheet->mergeCells('A'.$row.':G'.($row+1));
        $sheet->setCellValue('A'.$row, $Arqueo->comentario);
        $sheet->getStyle('A'.$row.':G'.($row+1))->applyFromArray($borde);

        /* ================= FIRMAS ================= */
        $row += 4;

        $sheet->mergeCells('A'.$row.':C'.$row);
        $sheet->mergeCells('E'.$row.':G'.$row);
        $sheet->setCellValue('A'.$row, '______________________________');
        $sheet->setCellValue('E'.$row, '______________________________');

        $row++;
        $sheet->setCellValue('A'.$row, 'FIRMA DEL PROMOTOR');
        $sheet->setCellValue('E'.$row, 'FIRMA DEL OPERATIVO');

        $row++;
        $sheet->setCellValue('A'.$row, strtoupper($Promotor));
        $sheet->setCellValue('E'.$row, strtoupper($Operativo));

        /* ================= DESCARGA ================= */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ArqueoPromotora.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
    }

    
}