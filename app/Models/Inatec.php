<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class Inatec extends Model {
    protected $table = "tbl_payroll_inatec";
    protected $connection = 'mysql';
    public $timestamps = false;
}
