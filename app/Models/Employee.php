<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Employee extends Model {
    protected $table = "tbl_employee";
    protected $connection = 'mysql';
    public $timestamps = false;
    protected $primaryKey = 'id_employee';


    public function ListPayrollType()
    {
        return $this->hasMany(PayrollEmploye::class, 'employee_id','id_employee');
    }


    public static function SaveEmployee(Request $request)
    {
            try {
                DB::transaction(function () use ($request) {
                    $r = Employee::insert([
                        'first_name' => $request->input('nombres'),
                        'last_name' => $request->input('apellidos'),
                        'phone_number' => $request->input('telefono'),
                        'cedula_number' => $request->input('cedula'),
                        'inss_number' => $request->input('num_inss'),
                        'email' => $request->input('email'),
                        'address' => $request->input('direccion'),
                        'active' => $request->input('isActivo'),
                        'position_id' => 0,
                        'contract_type_id' => 0,
                        'salario_mensual' => number_format(str_replace(',', '', $request->input('Salario_Mensual')),2,'.',''),
                    ]);
                }); 
                
            } catch (Exception $e) {
                dd($e->getMessage());
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        
    }
    public static function UpdateEmployee(Request $request)
    {

        try {
            DB::transaction(function () use ($request) {

                $id_employee        = $request->id_employee;
            
                Employee::where('id_employee',  $id_employee)->update([
                    'first_name'            => $request->nombres,
                    'last_name'             => $request->apellidos,
                    'phone_number'          => $request->telefono,
                    'cedula_number'         => $request->cedula,
                    'inss_number'           => $request->num_inss,
                    'email'                 => $request->email,
                    'address'               => $request->direccion,
                    'active'                => $request->isActivo,
                    'salario_mensual'       => number_format(str_replace(',', '', $request->input('Salario_Mensual')),2,'.','')
                ]);
                

                
            }); 

            
        } catch (Exception $e) {

            DD($e->getMessage());
            
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
            return response()->json($mensaje);
        }
        
    }
    
    public static function rmEmployee(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Id           = $request->input('id_');
                $response =  Employee::where('id_employee',  $Id)->update([
                    "active" => 2,
                ]);
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function Assigned($id)
    {
        $UsersAssigned = Assigned::where('users_id',$id)->pluck('employee_id')->toArray(); 

        return Employee::whereIn('id_employee',$UsersAssigned)->get();;

    }

}
