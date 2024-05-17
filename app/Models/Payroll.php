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
    public function PayrollDetails()
    {
        return $this->hasMany(Payroll_details::class, 'payroll_id', 'id_payrolls');
    }
    public static function setNamePayroll(Request $request)
    {
        $dtIni = $request->payroll_date_ini_;

        return date('d', strtotime($dtIni)) <= 15 ? "1Q-" . date('M-y', strtotime($dtIni)) : "2Q-" . date('M-y', strtotime($dtIni));
    }
    public static function setPayrollDetails($Id_payroll)
    {
        $Payrolls = Payroll::where('id_payrolls',$Id_payroll)->first();

        $Key = 0 ;

        $Employes = $Payrolls->PayrollEmploye;

        foreach ($Employes as $p) {
            if(isset($p->Employee->first_name)){
                $Comisiones = 0;
                $NetoPagar  = $Comisiones + $p->Employee->salario_mensual;
                $Vacaciones = ( $NetoPagar / 30 ) * 2.5 ;
                $Aguinaldo  = ( $NetoPagar / 12 ) ;
                $Indenizacion = ( $NetoPagar / 12 ) ;
    
                $datos_a_insertar[$Key] = [
                    'payroll_id'            => $Id_payroll,
                    'employee_full_name'    => $p->Employee->first_name . " " . $p->Employee->last_name,
                    'employee_position'     => '',
                    'cedula'                => $p->Employee->cedula_number,
                    'inns'                  => $p->Employee->inss_number,
                    'mes'                   => '',
                    'fecha'                 => null,
                    'salario_mensual'       => $p->Employee->salario_mensual,
                    'dias_trabajados'       => 0,
                    'salario_quincenal'     => ($p->Employee->salario_mensual / 2 ),
                    'comision'              => $Comisiones,
                    'neto_pagar'            => $NetoPagar,
                    'vacaciones'            => $Vacaciones,
                    'aguinaldo'             => $Aguinaldo,
                    'indenmnizacion'        => $Indenizacion,
                ];
                $Key++;
            }
            
            
        }
        Payroll_details::where('payroll_id', $Id_payroll)->delete();
        Payroll_details::insert($datos_a_insertar);
    }
    public static function SavePayroll(Request $request)
    {
            try {

                DB::transaction(function () use ($request) {

                    $idInsertado = Payroll::insertGetId([
                        'company_id'            => 0,
                        'payroll_type_id'       => $request->payroll_type_,
                        'payroll_status_id'     => 1,
                        'payroll_name'          => self::setNamePayroll($request),
                        'start_date'            => $request->payroll_date_ini_,
                        'end_date'              => $request->payroll_date_end_,
                        'inss_patronal'         => $request->payroll_inss_patronal_,
                        'Inatec'                => $request->payroll_inactec_,
                        'observation'           => $request->payroll_observation_,
                        'user_id'               => Auth::User()->id,
                        'active'                => 1,
                        'updated_at'            => date('Y-m-d H:i:s'),
                        'created_at'            => date('Y-m-d H:i:s'),
                    ]);

                    self::setPayrollDetails($idInsertado);
                });
                
                
                
            } catch (Exception $e) {
                dd($e->getMessage());
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        
    }
    public static function RemovePayroll(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Id           = $request->input('id_');
                $response =  Payroll::where('id_payrolls',  $Id)->update([
                    "active" => 0,
                ]);
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
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
