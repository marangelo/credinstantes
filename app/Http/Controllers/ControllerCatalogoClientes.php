<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Auth;
use Session; 
use App\Models\Clientes;
use App\Models\Zonas;
use App\Models\Municipios;
use App\Models\ClientesNegocio;
use App\Models\ClientesConyugue;
use App\Models\ClientesGarantia;
use App\Models\ClientesReferencias;
use App\Models\Departamentos;

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

    public function FiltrarClientes(Request $request)
    {
        $id_zona       = $request->input('id_zona');
        
        $Clientes   = Clientes::whereIn('activo', [1])->when($id_zona > 0, function ($query) use ($id_zona) {
            $query->where('id_zona', $id_zona);
        })->get();  

        $arryClientes = [];

        $Show = (in_array(Session::get('rol'), [1, 3])) ? true : false ;

        foreach ($Clientes as $c) {

            $Estado = '<span class="badge ' . ($c->getCreditos->count() > 0 ? 'bg-' . ['success','danger','warning',''][$c->getCreditos->first()->estado_credito - 1] : '') . ' ">
                            ' . ($c->getCreditos->count() > 0 ? strtoupper($c->getCreditos->first()->Estado->nombre_estado) : '-') . '
                        </span>';


            $Acciones = '<button class="btn p-0 text-info" type="button" onClick="Editar('. $c->id_clientes .' )"><span class="text-500 fas fa-edit"></span></button>
                        <button class="btn p-0 text-red ms-2" type="button"onClick="Remover('. $c->id_clientes.' )"><span class="text-500 fas fa-trash-alt"></span></button>';

            $arryClientes[] = [
                'id_clientes' => $c->id_clientes,
                'nombre' => $c->nombre,
                'apellidos' => $c->apellidos,                
                'direccion_domicilio' => $c->direccion_domicilio,
                'estado' =>$Estado,
                'ciclo' => $c->getCreditos->count(),
                'telefono' => $c->telefono,
                'cedula' => $c->cedula,
                'acciones' => ($Show) ? $Acciones : '' 
            ];
        }

        return response()->json($arryClientes);
    }

    public function FormClientes($id)
    {
        $Titulo = "Editar Cliente";
        $Cliente = Clientes::find($id);  
        $Municipios  = Municipios::getMunicipios();
        $Departamentos = Departamentos::getDepartamentos();
        return view('ClientesCatalogo.FormClientes', compact('Cliente','Titulo','Departamentos','Municipios'));
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
    public function rmReferencia(Request $request)
    {        
        $response = ClientesReferencias::rmReferencia($request);
        return response()->json($response);
    }

    public function UpdateCliente(Request $request)
    {

        $id_cli     = $request->input('id_clientes');

        $iGeneral   = $request->input('iGeneral');
        $iNegocio   = $request->input('iNegocio');
        $iConyugue  = $request->input('iConyugue');
        $iGarantias = $request->input('iGarantias');
        $iReferencias = $request->input('iReferencias');


        Clientes::updateOrCreate(
            ['id_clientes' => $id_cli],
            [
                'nombre'                => $iGeneral['nombres'],
                'apellidos'             => $iGeneral['apellidos'],
                'cedula'                => $iGeneral['cedula'],
                'telefono'              => $iGeneral['telefono'],
                'id_municipio'          => $iGeneral['selectMunicipio'],
                'id_departamento'       => $iGeneral['selectDepartamento'],
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

        if(isset($iReferencias) && $iReferencias != null){
            foreach ($iReferencias as $ref) {
                ClientesReferencias::updateOrCreate(
                    [
                        'nombre_ref' => $ref['nombre_ref']

                    ],
                    [
                        'id_cliente' => $id_cli,
                        'direccion_ref' => $ref['direccion_ref'],
                        'telefono_ref' => $ref['telefono_ref']
                    ]
                );
            }
        }

        return response()->json(['message' => 'Informacion Actualizada Correctamente'], 200);






        
    }

}