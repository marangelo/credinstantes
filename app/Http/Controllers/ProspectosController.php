<?php
namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Models\Zonas;
use App\Models\Prospectos;
use App\Models\Municipios;
use App\Models\DiasSemana;
use App\Models\Usuario;
use App\Models\Credito;
use App\Models\Clientes;
use App\Models\RequestsCredit;


class ProspectosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ProspectosView(){

        $Titulo      = "Clientes Prospectos";
        $Municipios  = Municipios::getMunicipios();  
        $DiasSemana  = DiasSemana::getDiasSemana();
        $Zonas       = Zonas::getZonas();  
        $Promo       = Usuario::where('activo','S')->get(); 
        $Usuarios    = Usuario::where('activo','S')->whereNotIn('id_rol', [1])->get();
        return view('Clientes_Prospectos.Home', compact('Titulo','Zonas','Municipios','DiasSemana','Promo','Usuarios'));

    }

    
    public function getProspectos(Request $request)
    {
        $response = Prospectos::getProspectos($request);
        
        return response()->json($response);
    }
    public function FormPospecto($IdProspecto){
        $Titulo      = "Clientes Prospectos";
        $Zonas       = Zonas::getZonas();
        $Prospecto   = Prospectos::where('id_prospecto', $IdProspecto)->first();
        
        return view('Clientes_Prospectos.Form', compact('Titulo','Prospecto','Zonas'));
    }

    
    public function SaveProspecto(Request $request)
    {
        //$cedula = $request->input('Cedula_');
        //$IdPros = $request->input('IdProspecto_');


        // if ($IdPros > 0) {
        //     $response = Prospectos::SaveProspecto($request);
        // } else {
        //     // Check if the prospect exists in the Prospectos model
        //     $prospecto = Prospectos::where('Cedula', $cedula)->where('activo', 1)->first();
            
        //     // If not found in Prospectos, check in the Clientes model
        //     if (!$prospecto) {
        //         $cliente = Clientes::where('Cedula', $cedula)->where('activo', 1)->first();
        //         if (!$cliente) {
        //             // If not found in Clientes, proceed to save the prospect
        //             $response = Prospectos::SaveProspecto($request);
        //         } else {
        //             return response()->json(['exists' => true, 'message' => 'Prospecto Encontrado como Clientes.']);
        //         }
        //     } else {
        //         return response()->json(['exists' => true, 'message' => 'Prospecto encontrado.']);
        //     }
            
        // }

        $response = Prospectos::SaveProspecto($request);
        
        return response()->json(['exists' => false, 'message' => 'Registro Grabado Correctamente.']);


        
        //return response()->json($response);
    }
    public function DeleteProspecto($IdProspecto)
    {
        $response = Prospectos::UpdateEstadoProspecto($IdProspecto,0);        
        
        if($response){
            return redirect()->route('Prospectos');
        }
    }

    public function getInfoProspecto($IdProspecto)
    {
        $response = Prospectos::getInfoProspecto($IdProspecto);
        
        return response()->json($response);
    }
    public function SaveNewProspecto(Request $request)
    {

        $IdProspecto           = $request->input('IdProspecto_');

        $messages = [
            'FechaOpen.required'    => 'La fecha de apertura.',
            'DiaSemana_.required'   => 'El día de la semana.',
            'Promotor_.required'    => 'El promotor.',
            'Nombre_.required'      => 'El nombre.',
            'Apellido_.required'    => 'El apellido.',
            'Tele_.required'        => 'El teléfono.',
            'Cedula_.required'      => 'La cédula.',
            'Municipio_.required'   => 'El municipio.',
            'Zona_.required'        => 'La zona.',
            'Dire_.required'        => 'La dirección.',
            'Monto_.required'       => 'El monto.',
            'Plato_.required'       => 'El plazo.',
            'Interes_.required'     => 'El interés.',
            'Cuotas_.required'      => 'Las cuotas.',
            'Total_.required'       => 'El total.',
            'vlCuota.required'      => 'El valor de la cuota.',
            'Saldos_.required'      => 'El saldo.',
            'vlInteres.required'    => 'El valor de interés.',
            'InteresesPorCuota.required' => 'El interés por cuota.',
        ];

        $request->validate([
            'FechaOpen'    => 'required|date',
            'DiaSemana_'   => 'required|string|max:20',
            'Promotor_'    => 'required|string|max:100',
            'Nombre_'      => 'required|string|max:50',
            'Apellido_'    => 'required|string|max:50',
            'Tele_'        => 'required|string|max:20',
            'Cedula_'      => 'required|string|max:20',
            'Municipio_'   => 'required|string|max:50',
            'Zona_'        => 'required|string|max:100',
            'Dire_'        => 'required|string',
            'Monto_'       => 'required|numeric',
            'Plato_'       => 'required|numeric',
            'Interes_'     => 'required|numeric',
            'Cuotas_'      => 'required|numeric',
            'Total_'       => 'required|numeric',
            'vlCuota'      => 'required|numeric',
            'Saldos_'      => 'required|numeric',
            'vlInteres'    => 'required|numeric',
            'InteresesPorCuota' => 'required|numeric',
        ], $messages);

        $creditRequest = RequestsCredit::create([
            'req_start_date'      => $request->FechaOpen,
            'visit_day'           => $request->DiaSemana_,
            'promoter'            => $request->Promotor_,
            'first_name'          => $request->Nombre_,
            'last_name'           => $request->Apellido_,
            'phone'               => $request->Tele_,
            'num_cedula'          => $request->Cedula_,
            'id_department'       => $request->Municipio_,
            'id_zone'             => $request->Zona_,
            'client_address'      => $request->Dire_,
            'monto'               => $request->Monto_,
            'plazo'               => $request->Plato_,
            'interes_porcent'     => $request->Interes_,
            'num_cuotas'          => $request->Cuotas_,
            'total'               => $request->Total_,
            'cuota'               => $request->vlCuota,
            'saldo'               => $request->Saldos_,
            'interes_valor'       => $request->vlInteres,
            'intereses_por_cuota' => $request->InteresesPorCuota,
            'activo'              => 1,
            'created_by'          => Auth::id(),
            'Origen'              => $request->_Origin,
            'id_cliente'          => $request->IdClientes	
        ]);

        Prospectos::UpdateEstadoProspecto($IdProspecto,2);  
        
        return response()->json([
            'message' => 'Solicitud de crédito registrada con éxito',
            'data' => $creditRequest
        ], 201);

    

        
    }

}