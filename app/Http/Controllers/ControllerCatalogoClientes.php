<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;


use App\Models\Clientes;
use App\Models\Zonas;
use App\Models\Municipios;
use App\Models\ClientesNegocio;
use App\Models\ClientesConyugue;
use App\Models\ClientesGarantia;

class ControllerCatalogoClientes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function ViewCatalogoClientes()
    {         
        $Titulo     = "Catalogo de Clientes";
        $Clientes   = Clientes::whereIn('activo', [1])->get();
        $Zonas      = Zonas::getZonas(); 
        return view('ClientesCatalogo.Table', compact('Titulo', 'Clientes','Zonas'));
        
    }

    public function FormClientes($id)
    {
        $Titulo = "Editar Cliente";
        $Cliente = Clientes::find($id);  
        $Municipios  = Municipios::getMunicipios();
        return view('ClientesCatalogo.FormClientes', compact('Cliente','Titulo','Municipios'));
    }
    public function AddCliente()
    {   
        $Titulo = "Nuevo Cliente";
        return view('ClientesCatalogo.FormClientes', compact('Titulo'));
    }
    public function rmGarantia(Request $request)
    {        
        $response = ClientesGarantia::rmGarantia($request);
        return response()->json($response);
    }

    public function UpdateCliente(Request $request)
    {

        $id_cli     = $request->input('id_clientes');

        $iGeneral   = $request->input('iGeneral');
        $iNegocio   = $request->input('iNegocio');
        $iConyugue  = $request->input('iConyugue');
        $iGarantias = $request->input('iGarantias');


        Clientes::updateOrCreate(
            ['id_clientes' => $id_cli],
            [
                'nombre'                => $iGeneral['nombres'],
                'apellidos'             => $iGeneral['apellidos'],
                'cedula'                => $iGeneral['cedula'],
                'telefono'              => $iGeneral['telefono'],
                'id_municipio'          => $iGeneral['selectMunicipio'],
                'estado_civil'          => $iGeneral['selectEstadoCivil'],
                'direccion_domicilio'   => $iGeneral['direccion'],
                
            ] 
        );

        ClientesNegocio::updateOrCreate(
            ['id_cliente' => $id_cli],
            [
                'nombre_negocio'    => $iNegocio['nombre_negocio'],
                'antiguedad'        => $iNegocio['Antiguedad_negocio'],
                'direccion'         => $iNegocio['direccion_negocio']              
            ] 
        );
        
        ClientesConyugue::updateOrCreate(
            ['id_cliente' => $id_cli],
            [
                'nombres'            => $iConyugue['nombres_conyugue'],
                'apellidos'          => $iConyugue['apellidos_conyugue'],
                'no_cedula'          => $iConyugue['cedula_conyugue'],
                'telefono'           => $iConyugue['telefono_conyugue'],    
                'direccion_trabajo'  => $iConyugue['direccion_conyugue']     
            ] 
        );

        if(isset($iGarantias) && $iGarantias != null){
            foreach ($iGarantias as $articulo) {
                ClientesGarantia::updateOrCreate(
                    [
                        'detalle_articulo' => $articulo['detalle_articulo']

                    ],
                    [
                        'id_cliente' => $id_cli,
                        'marca' => $articulo['marca'],
                        'color' => $articulo['color'],
                        'valor_recomendado' => $articulo['valor_recomendado']
                    ]
                );
            }
        }

        return response()->json(['message' => 'Informacion Actualizada Correctamente'], 200);






        
    }

}