<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class RefAbonosCancelados extends Model {
    public $timestamps = false;
    protected $table = "tbl_abonos_cancelados";    
    protected $primaryKey = 'ID_CREDITO';

}