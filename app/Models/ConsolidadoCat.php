<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConsolidadoCat extends Model {
    public $timestamps = false;
    protected $table = "tbl_consolidados_label";
}
