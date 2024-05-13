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

    public function Contract()
    {
        return $this->belongsTo(Contract::class, 'contract_type_id','id_contract_type');
    }
    public function ListPayrollType()
    {
        return $this->hasMany(PayrollEmploye::class, 'employee_id','id_employee');
    }
    public function Position()
    {
        return $this->belongsTo(Position::class, 'position_id','id_position');
    }

    public static function SaveEmployee(Request $request)
    {
            try {
                DB::transaction(function () use ($request) {
                    $posicion           = $request->input('posicion');
                    $contrato           = $request->input('contrato');

                    $nombres            = $request->input('nombres');
                    $apellidos          = $request->input('apellidos');
                    $telefono           = $request->input('telefono');
                    $cedula             = $request->input('cedula');
                    $num_inss           = $request->input('num_inss');
                    $email              = $request->input('email');
                    $direccion          = $request->input('direccion');
                    $Vacaciones         = $request->input('Vacaciones');
                    $date_in            = $request->input('date_in');
                    $date_out           = $request->input('date_out');
                    $genero             = $request->input('genero');
                    $nacionalidad       = $request->input('nacionalidad');
                    $talla_camisa       = $request->input('talla_camisa');
                    $talla_pantalon     = $request->input('talla_pantalon');
                    $activo             = $request->input('activo');

                    

                    $toInsert = [
                        'position_id'           => $posicion ,
                        'contract_type_id'      => $contrato ,
                        'first_name'            => $nombres ,
                        'last_name'             => $apellidos ,
                        'phone_number'          => $telefono ,
                        'cedula_number'         => $cedula ,
                        'inss_number'           => $num_inss ,
                        'email'                 => $email ,
                        'address'               => $direccion ,
                        'vacation_balance'      => $Vacaciones ,
                        'date_in'               => $date_in ,
                        'date_out'              => $date_out ,
                        'gender'                => $genero ,
                        'nationality'           => $nacionalidad ,
                        'shirt_size'            => $talla_camisa ,
                        'pants_size'            => $talla_pantalon ,
                        'active'                => $activo
                    ];
                    Employee::insert($toInsert);
                    Employee::UploadAWS($request);
                }); 
                
            } catch (Exception $e) {
                
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
                        'position_id'           => $request->posicion,
                        'contract_type_id'      => $request->contrato,
                        'first_name'            => $request->nombres,
                        'last_name'             => $request->apellidos,
                        'phone_number'          => $request->telefono,
                        'cedula_number'         => $request->cedula,
                        'inss_number'           => $request->num_inss,
                        'email'                 => $request->email,
                        'address'               => $request->direccion,
                        'vacation_balance'      => $request->Vacaciones,
                        'date_in'               => $request->date_in,
                        'date_out'              => $request->date_out,
                        'gender'                => $request->genero,
                        'nationality'           => $request->nacionalidad,
                        'shirt_size'            => $request->talla_camisa,
                        'pants_size'            => $request->talla_pantalon,
                        'active'                => $request->activo
                    ]);
                    

                    
                }); 

                
            } catch (Exception $e) {
                
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        
    }
    public static function UploadAWS(Request $request)
    {
        $id_employee        = $request->input('id_employee');

        if($request->hasFile('photo_employee')){
            $info_Employee = Employee::where('id_employee',  $id_employee)->first();                        
            $Departamento   = Str::slug($info_Employee->Position->Department->department_name,'_');

            $name_employee = $info_Employee->first_name. ' ' . $info_Employee->last_name;
            $name_employee = Str::slug($name_employee, '_');

            $file = $request->file('photo_employee');
            $fileExtension = Str::slug($file->getClientOriginalExtension());

            $AWS_PATH = 'Planilla/' . $Departamento . '/' .$name_employee.'.'.$fileExtension;

            if (Storage::disk('s3')->exists($info_Employee->path_image)) {
                Storage::disk('s3')->delete($info_Employee->path_image);
            }

            Employee::where('id_employee',  $id_employee)->update([
                "path_image" => $AWS_PATH,
            ]);
            
            Storage::disk('s3')->put($AWS_PATH, file_get_contents($file));
            
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
