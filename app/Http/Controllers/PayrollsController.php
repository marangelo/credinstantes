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
        
        $retView = ($Payrolls->payroll_type_id === 1) ? 'Payroll.Quincenal' : 'Payroll.Depreciacion ' ;

        $Titulo = $Payrolls->Type->payroll_type_name;

        $Employes = $Payrolls->PayrollDetails;
        
        return view($retView,compact('Employes','Payrolls','Titulo'));
    }
    public function UpdatePayroll(Request $request)
    {
        $Type_PayRoll = $request->input('id_Type_PayRoll_');

        $response = ($Type_PayRoll == 1) ? Payroll_details::UpdateQuincenal($request) : Payroll_details::UpdateDepreciacion($request) ;

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
        $Type_PayRoll = $request->input('TypePayRoll');

        $response = ($Type_PayRoll == 1) ? Payroll::ExportPayrollQuincenal($request) : Payroll::ExportPayrollDepreciacion($request) ;

    }

    public function ProcessPayroll(Request $request)
    {        
        $response = Payroll::ProcessPayroll($request);
        return response()->json($response);
    }
}  