<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

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

                $response = Arqueo::where('id_arqueo',$Arqueo)->update([
                    "fecha_arqueo"          => $Fecha,
                    "deposito_dia"          => $Deposit,
                    "deposito_tranferencia" => $Tranfe,
                    "gasto_operacion"       => $Gastos
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