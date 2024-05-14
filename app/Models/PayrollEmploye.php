<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class PayrollEmploye extends Model {
    protected $table = "tbl_employe_payroll";
    protected $connection = 'mysql';
    public $timestamps = false;
    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id','id_employee')->where('active', 1);
    }
}
