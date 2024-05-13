<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Payroll extends Model {
    protected $table = "tbl_payrolls";
    protected $connection = 'mysql';
    protected $primaryKey = 'id_payrolls';
    public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id','id_compy');
    }
    public function Status()
    {
        return $this->belongsTo(PayrollStatus::class, 'payroll_status_id','id_payroll_status');
    }
    public function Type()
    {
        return $this->belongsTo(PayrollType::class, 'payroll_type_id','id_payroll_type');
    }
    public function PayrollEmploye()
    {
        return $this->hasMany(PayrollEmploye::class, 'payrolls_id','payroll_type_id');
    }

    public static function SavePayroll(Request $request)
    {
            try {
                DB::transaction(function () use ($request) {
                    $p = new Payroll();
                    $p->company_id              = $request->payroll_commpany_;
                    $p->payroll_type_id         = $request->payroll_type_;
                    $p->payroll_status_id       = 1;
                    $p->payroll_name            = 'N/D';
                    $p->start_date              = $request->payroll_date_ini_;
                    $p->end_date                = $request->payroll_date_end_;
                    $p->inss_patronal           = $request->payroll_inss_patronal_;
                    $p->Inatec                  = $request->payroll_inactec_;
                    $p->observation             = $request->payroll_observation_;
                    $p->user_id                 = Auth::User()->id;
                    $p->save();
                    
                }); 
                
            } catch (Exception $e) {
                
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        
    }
    public static function EmployeeTypePayroll(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Employee       = $request->Employee_;
                $PayrollType    = $request->PayrollType_;
                $isChecked      = $request->isChecked_;
            

                if ($isChecked === "true") {
                    $p = new PayrollEmploye();
                    $p->employee_id     = $Employee;
                    $p->payrolls_id     = $PayrollType;
                    $p->save();
                } else {
                    PayrollEmploye::where('employee_id',  $Employee)->where('payrolls_id',  $PayrollType)->delete();
                }
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
}
