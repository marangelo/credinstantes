<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class EstadosMonitor extends Model {
    public $timestamps = false;
    protected $table = "view_status_cliente";    

}
