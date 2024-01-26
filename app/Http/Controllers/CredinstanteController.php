<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Clientes;
use App\Models\Municipios;
use App\Models\Departamentos;
use App\Models\Zonas;
use App\Models\DiasSemana;
use App\Models\Abono;
use App\Models\Credito;
use App\Models\Credinstante;
use App\Models\Usuario;
use App\Models\PagosFechas;
use App\Models\Roles;
use App\Models\DateRecord;
use CodersFree\Date\Date;

class CredinstanteController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getDashboard()
    {         
        $IsCalc = DateRecord::Check();
        $Zonas  = Zonas::getZonas();  
   

        $View = ($IsCalc) ? 'Dashboard.update' : 'Dashboard.home' ;

        $Titulo = "Dashboard";
        return view($View,compact('Titulo','Zonas'));
        
    }
    public function Promotor()
    {         
        $Titulo = "Promotor";
        
        $Clientes = Clientes::Clientes_promotor();
        return view('Promotor.home',compact('Titulo','Clientes'));

        
        
    }

    public function prtVoucher($Id){
        $Abono    = Abono::find($Id); 
        \Log::channel('log_vouchers')->info("Se imprimio el Voucher del pago complete de: ". $Id);
        return view('Voucher.completo', compact('Abono'));
    }

    public function prtVoucherParcial($Id){
        $Abono    = Abono::find($Id); 
        \Log::channel('log_vouchers')->info("Se imprimio el Voucher del pago Parcial de: ". $Id);
        return view('Voucher.parcial', compact('Abono'));
        
    }
    
    public function getClientes()
    {   
        $IsCalc      = DateRecord::Check();
        $Clientes    = Clientes::getClientes();  
        $Municipios  = Municipios::getMunicipios();  
        $DiasSemana  = DiasSemana::getDiasSemana();
        $Zonas       = Zonas::getZonas();  
        $Titulo      = "Clientes Activos";
        $View        = ($IsCalc) ? 'Dashboard.update' : 'Clientes.ls_Clientes' ;    
        
        return view( $View, compact('Clientes','Municipios','DiasSemana','Zonas','Titulo'));
        
    }
    public function getInactivos()
    {   
        $Clientes    = Clientes::getInactivos();  
        $Municipios  = Municipios::getMunicipios();  
        $DiasSemana  = DiasSemana::getDiasSemana();  
        $Zonas       = Zonas::getZonas();  
        $Titulo      = "Clientes Inactivos";
        
        return view('Clientes.ls_Clientes', compact('Clientes','Municipios','DiasSemana','Zonas','Titulo'));
        
    }

    public function getPerfil($id)
    {   
        
        $perfil_cliente = Clientes::find($id);  
        $DiasSemana     = DiasSemana::getDiasSemana();
        $Titulo         = "Perfil del Clientes";
    
        return view('Clientes.Perfil', compact('perfil_cliente','DiasSemana','Titulo'));
        
    }
    public function getMunicipios()
    {   
        $Municipios = Municipios::getMunicipios(); 
        $Departamentos = Departamentos::getDepartamentos();  
        $Titulo         = "DEPARTAMENTOS";
        
        
        return view('Clientes.ls_Municipios', compact('Municipios','Departamentos','Titulo'));
        
    }
    public function getDepartamento()
    {   
        $Departamentos = Departamentos::getDepartamentos();  
        $Titulo         = "Departamento";
        
        return view('Clientes.ls_Departamentos', compact('Departamentos','Titulo'));
        
    }
    public function getZona()
    {   
        $Zonas = Zonas::getZonas();  
        $Titulo         = "Zonas";
        
        return view('Clientes.ls_zonas', compact('Zonas','Titulo'));
        
    }
    public function addZona(Request $request)
    {
        $response = Zonas::addZona($request);
        
        return response()->json($response);
    }

    public function rmZona($id)
    {
        $response = Zonas::rmZona($id);
        
        return response()->json($response);
    }
    public function getDiasSemna()
    {   
        $DiasSemana = DiasSemana::getDiasSemana();  
        $Titulo         = "Dia de Semana";
        
        return view('Clientes.ls_DiasSemana', compact('DiasSemana','Titulo'));
        
    }
    public function getUsuarios ()
    {          
        $Titulo         = "USUARIOS";
        $Usuarios       = Usuario::getUsuarios();
        $Roles          = Roles::getRoles();
        $Zonas          = Zonas::getZonas();
        return view('Usuario.lista',compact('Titulo','Usuarios','Roles','Zonas'));
        
    }

    public function SaveNewCredito(Request $request)
    {
        $response = Credito::SaveNewCredito($request);
        
        return response()->json($response);
    }

    public function editClient(Request $request)
    {
        $response = Clientes::editClient($request);
        
        return response()->json($response);
    }

    public function SaveNewMunicipio(Request $request)
    {
        $response = Municipios::SaveNewMunicipio($request);
        
        return response()->json($response);
    }

    public function rmMunicipio($id)
    {
        $response = Municipios::rmMunicipio($id);
        
        return response()->json($response);
    }

    public function SaveNewDepartamento(Request $request)
    {
        $response = Departamentos::SaveNewDepartamento($request);
        
        return response()->json($response);
    }

    public function rmDepartamento($id)
    {
        $response = Departamentos::rmDepartamento($id);
        
        return response()->json($response);
    }

    public function AddDiaSemana(Request $request)
    {
        $response = DiasSemana::AddDiaSemana($request);
        
        return response()->json($response);
    }

    public function AddCredito(Request $request)
    {
        $response = Credito::AddCredito($request);
        
        return response()->json($response);
    }

    public function rmDiaSemana($id)
    {
        $response = DiasSemana::rmDiaSemana($id);
        
        return response()->json($response);
    }
    public function MultiAbonos(Request $request)
    {

        $response =Abono::MultiAbonos($request) ;
        return response()->json($response);
    }
    public function Bluid(Request $request)
    {

        $response =Abono::Bluid($request) ;
        return response()->json($response);
    }

   

    public function SaveNewAbono(Request $request)
    {

        $Tipo   = $request->input('Tipo');

        switch ($Tipo) {
            case '0':
                $response = Abono::SaveNewAbono($request);
                Clientes::CheckStatus($request->input('IdCred'));
                break;
            case '1':
                $response = Abono::Cancelacion($request);
                break;
            case '2':
                $response = Abono::MultiAbonos($request);
                Clientes::CheckStatus($request->input('IdCred'));
                break;
            
            default:
                # code...
                break;
        }
        
        

        return response()->json($response);
    }
    public function getHistoricoAbono($IdCredito)
    {
        //$Abonos =  

        $dta[] = array(
            'Abonos' => Abono::getHistorico($IdCredito),
            'Pagos' => PagosFechas::getFechasPagos($IdCredito)
        );

        return response()->json($dta);
    }

    public function Remover(Request $request)
    {
        $response = Credinstante::Remover($request);
        
        return response()->json($response);
    }
    public function rmAbono(Request $request)
    {
        $response = Abono::rmAbono($request);
        
        return response()->json($response);
    }

    public function creditCheck(Request $request)
    {
        $response = Clientes::creditCheck($request);
        
        return response()->json($response);
    }

    public function ChanceStatus(Request $request)
    {
        $response = Credito::ChanceStatus($request);
        
        return response()->json($response);
    }

    public function getAllCredit(Request $request)
    {
        $IdCl_           = $request->input('Cliente_');
        $Credito_Cliente = Clientes::find($IdCl_);
        
        return response()->json($Credito_Cliente->getCreditos);
    }
    public function updatePassword(Request $request)
    {
        $response = Usuario::updatePassword($request);
        
        return response()->json($response);
    }
    public function getSaldoAbono($IdCredito,$opt)
    {
        $Abonos =  Abono::getSaldoAbono($IdCredito,$opt);

        return response()->json($Abonos);
    }

    public function AddNewUser(Request $request)
    {
        $response = Usuario::SaveUsuario($request);
        
        return response()->json($response);
    }
    


}  