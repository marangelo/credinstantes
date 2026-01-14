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

    public static function getData(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';
        $IdZna    = $request->input('IdZna');

        $Obj =  ArqueoPromotor::whereBetween('fecha', [$dtIni, $dtEnd])->Where('activo',1);

        // if ($IdZna > 0) {
        //     $Obj->Where('id_zona',$IdZna);
        // }

        $Arqueos = $Obj->get();

        $array_arqueos = array();
        
        foreach ($Arqueos as $key => $a) 
        {  
            //$name_user_arqueo = (empty($a->getZona->UsuarioCobrador->nombre)) ? 'N/D' : $a->getZona->UsuarioCobrador->nombre ;

            $name_user_arqueo = 'PROMOTOR ' . $a->id_arqueo_prom;

            $array_arqueos[$key] = [
                "Id"                => $a->id_arqueo_prom,
                "fecha_arqueo"      => \Date::parse($a->fecha)->format('d-m-Y') ,
                "Zona"              => $a->id_arqueo_prom,
                "Nombre"            => strtoupper($name_user_arqueo),
                
                "entregado"         => $a->entregado,
                "desembolsado"      => $a->desembolsado,
                "sobrante"          => $a->sobrante,
                "consolidado"       => $a->consolidado,
            ];
            
        }


        return $array_arqueos;
    }
    
}