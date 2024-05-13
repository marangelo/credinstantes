<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class InssPatronal extends Model {
    protected $table = "tbl_payroll_inss_patronal";
    protected $connection = 'mysql';
    public $timestamps = false;
}
