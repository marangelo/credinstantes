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