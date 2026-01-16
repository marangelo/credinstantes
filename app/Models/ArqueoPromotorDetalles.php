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

class ArqueoPromotorDetalles extends Model {
    public $timestamps = false;
    protected $table = "tbl_arqueo_prom_detalles";
    protected $primaryKey = 'id_arqueo_prom_detalles';
}