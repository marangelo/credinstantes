<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class MetricasHistory extends Model {
    public $timestamps = false;
    protected $table = "tbl_metricas_history";    
    protected $primaryKey = 'id_metricas';


    public static function getMoraHistory($zona,$Mora)
    {
        try {
            $moraAtrasada = MetricasHistory::where('Zona_id', $zona)
                ->orderBy('id_metricas', 'DESC')
                ->value($Mora);

            return $moraAtrasada;
        } catch (Exception $e) {
            // Handle the exception
        }
    }

}
