<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class RefAbonos extends Model {
    public $timestamps = false;
    protected $table = "Tbl_PagosAbonos";    
    protected $primaryKey = 'id_pagoabono';

}