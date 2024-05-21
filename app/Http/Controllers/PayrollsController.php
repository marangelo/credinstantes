<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requests;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Auth;

use App\Models\PayrollType;
use App\Models\InssPatronal;
use App\Models\Inatec;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Payroll_details;


class PayrollsController extends Controller {
    public function __construct()
    {
        Date::setLocale('es');
        $this->middleware('auth');
    }
    public function getPayrolls(Request $request)
    {        
        $month = $request->query('month');
        $year = $request->query('year');

        $Payrolls = Payroll::where('active', 1)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        $PayRollType    = PayrollType::where('active',1)->get();   
        $Inactec        = Inatec::where('active',1)->first();  
        $InssParonal    = InssPatronal::where('active',1)->first();  

        $Titulo         = "Nómina";
        
        
        return view('Payroll.Home',compact('PayRollType','Inactec','InssParonal','Payrolls','Titulo'));
    }
    public function SavePayroll(Request $request)
    {        
        $response = Payroll::SavePayroll($request);
        return response()->json($response);
    }
    public function RemovePayroll(Request $request)
    {        
        $response = Payroll::RemovePayroll($request);
        return response()->json($response);
    }

    public function EditPayrolls($Id)
    {
        $Payrolls = Payroll::where('id_payrolls',$Id)->first();
        
        switch ($Payrolls->payroll_type_id) {
            case 1:
            $retView = 'Payroll.Quincenal';
            break;
            case 2:
            $retView = 'Payroll.Depreciacion';
            break;
            case 3:
            $retView = 'Payroll.Comiciones';
            break;
        }

        $Titulo = $Payrolls->Type->payroll_type_name;

        $Employes = $Payrolls->PayrollDetails;
        
        return view($retView,compact('Employes','Payrolls','Titulo'));
    }
    public function UpdatePayroll(Request $request)
    {
        $PayRoll_type = $request->input('PayRoll_type_');

        switch ($PayRoll_type) {
            case 1:
                $response = Payroll_details::UpdateQuincenal($request);
                break;
            case 2:
                $response = Payroll_details::UpdateDepreciacion($request);
                break;
            case 3:
                $response = Payroll_details::UpdateComisiones($request);
                break;
        }

        return response()->json($response);
    }
    public function IngresosEgresos($Id_Payroll,$Id_Employee)
    {
        $Employee = Employee::where('id_employee',$Id_Employee)->first();  
        
        return view('Payroll.IngresoEgreso',compact('Employee'));
    }

    public function EmployeeTypePayroll(Request $request)
    {        
        $response = Payroll::EmployeeTypePayroll($request);
        return response()->json($response);
    }

    public function ExportPayroll(Request $request)
    {
        $PayRoll_type = $request->input('TypePayRoll');

        switch ($PayRoll_type) {
            case 1:
                $response = Payroll::ExportPayrollQuincenal($request);
                break;
            case 2:
                $response = Payroll::ExportPayrollDepreciacion($request);
                break;
            case 3:
                $response = Payroll::ExportPayrollComisiones($request);
                break;
        }
    }

    public function ProcessPayroll(Request $request)
    {        
        $response = Payroll::ProcessPayroll($request);
        return response()->json($response);
    }
}  