<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class PayrollType extends Model {
    protected $table = "tbl_payroll_type";
    protected $connection = 'mysql';
    public $timestamps = false;
}
