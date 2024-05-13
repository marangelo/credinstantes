<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class PayrollStatus extends Model {
    protected $table = "tbl_payroll_status";
    protected $connection = 'mysql';
    public $timestamps = false;
}
