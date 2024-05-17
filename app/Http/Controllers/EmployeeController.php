<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Kardex;
use App\Models\Catalogos;
use App\Models\Employee;
use App\Models\PayrollType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getHome()
    {        
        return view('Employee.Form');
    }

    public function Employee()
    {        
        $Titulo = "Nuevo Empleado";
        $Employee = Employee::whereIn('active', [0, 1])->get();
        return view('Employee.Home',Compact('Employee','Titulo'));
    }

    public function AddEmployee()
    {   
        $Titulo = "Nuevo Empleado";
        $PayrollTypes = PayrollType::where('active',1)->get();
        return view('Employee.Form', compact('PayrollTypes','Titulo'));
    }

    public function SaveEmployee(Request $request)
    {
        Employee::SaveEmployee($request);
        return redirect()->route('AddEmployee')->with('message_success', 'Registro creado exitosamente :)');

    }
    public function UpdateEmployee(Request $request)
    {
        $id_employee        = $request->input('id_employee');

        Employee::UpdateEmployee($request);
        return redirect()->route('EditEmployee', ['id_employee' => $id_employee])->with('message_success', 'Registro Actualizado exitosamente :)');

    }
    
    
    public function rmEmployee(Request $request)
    {        
        $response = Employee::rmEmployee($request);
        return response()->json($response);
    }

    public function EditEmployee($id)
    {
        
        $Titulo = "Editar Empleado";
        $Employee = Employee::where('id_employee',$id)->first();          
        $PayrollTypes = PayrollType::where('active',1)->get();  
    
        return view('Employee.Form', compact('Employee','PayrollTypes','Titulo'));

    }

    public function formatFechaDiferencia($date)
    {
        return optional($date, function ($date) {
            $diferencia = Carbon::parse($date)->diff(Carbon::now());

            if ($diferencia->y < 1 && $diferencia->m < 1) {
                return $diferencia->format('%d Días');
            } elseif ($diferencia->y < 1) {
                return $diferencia->format('%m meses %d días');
            } else {
                return $diferencia->format('%y año, %m meses, %d días');
            }
        }) ?? '00/00/0000';
    }

    
}  