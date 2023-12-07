<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class Estados extends Model {
    public $timestamps = false;
    protected $table = "tbl_estados";    
    protected $primaryKey = 'id_estados';

}
