<?php

namespace App\Models;

use Auth;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class Prospectos extends Model {
    protected $table = "tbl_clientes_prospectos";
    protected $connection = 'mysql';
    public $timestamps = false;
    protected $primaryKey = 'id_prospecto';

    protected $fillable = [
        'Nombres',
        'Apellidos',
        'Direccion',
        'Cedula',
        'Telefono',
        'id_zona',
        'Monto_promedio',
        'activo',
        'created_by',
        'id_prospecto', 
        'updated_at',
        'created_at'
    ];
    public function getZona()
    {
        return $this->hasOne(Zonas::class, 'id_zona','id_zona')->where('activo',1);
    }
    public function User()
    {
        return $this->hasOne(Usuario::class, 'id','created_by');
    }

    public static function getProspectos(Request $request)
    {
    
        $IdZna          = $request->input('IdZna');

        $array_prospectos   = array();

        $Prospectos = Prospectos::where('activo', 1)->when($IdZna > 0, function ($q) use ($IdZna) {
            $q->where('id_zona', $IdZna);
        })->get();
        
        
        foreach ($Prospectos as $key => $c) {

            $array_prospectos[$key] = [
                "id_clientes"           => $c->id_prospecto,
                "Nombre"                => strtoupper($c->Nombres),
                "apellido"              => strtoupper($c->Apellidos),
                "Direccion"             => $c->Direccion,
                "Cedula"                => $c->Cedula,
                "Telefono"              => $c->Telefono,
                "Monto_promedio"        => $c->Monto_promedio,
                "Zona"                  => $c->getZona->nombre_zona,
                "Usuario"               => $c->User->nombre,
                "Fecha_registro"        => date('Y-m-d h:m:s', strtotime($c->created_at)),
                "Accion"                => '
                
                <a href="#!"' . $c->id_prospecto.' " class="btn btn-success btn-block" onclick="ModalProspecto(' . $c->id_prospecto.')" ><i class="fas fa-money-check-alt"></i> </a>
                <a href="FormPospecto/' . $c->id_prospecto.' " class="btn btn-primary btn-block" ><i class="fas fa-edit"></i> </a>
                <a href="deleteProspecto/' . $c->id_prospecto.' " class="btn btn-danger btn-block" ><i class="fas fa-trash"></i> </a>',
            ];
                
        }


        return $array_prospectos;
    }
    public static function getInfoProspecto($IdProspecto)
    {
        $Prospecto = Prospectos::where('id_prospecto', $IdProspecto)->first();
        return $Prospecto;
    }
    public static function SaveProspecto(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = [
                    'Nombres'          => $request->input('Nombre_'),
                    'Apellidos'        => $request->input('Apellido_'),
                    'Direccion'        => $request->input('Dire_'),
                    'Cedula'           => $request->input('Cedula_'),
                    'Telefono'         => $request->input('Tele_'),
                    'id_zona'          => $request->input('Zona_'),
                    'Monto_promedio'   => $request->input('Monto_'),
                    'activo'           => 1,
                    'created_by'       => Auth::id(),
                    'created_at'           => date('Y-m-d h:m:s'),
                    'updated_at'           => date('Y-m-d h:m:s'),
                ];
    
                // Usa updateOrCreate para actualizar o insertar
                $response = Prospectos::updateOrCreate(
                    ['id_prospecto' => $request->input('IdProspecto_')], // Busca por IdProspecto_
                    $data // Actualiza o inserta con estos valores
                );
    
                return response()->json(['success' => true, 'data' => $response], 200);
    
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Excepción capturada: ' . $e->getMessage()
                ], 500);
            }
        }
    
        return response()->json(['success' => false, 'message' => 'Solicitud no válida'], 400);
    }
    

    public static function UpdateEstadoProspecto($IdProspecto,$Estado)
    {        
        $response = Prospectos::where('id_prospecto', $IdProspecto)->update([
            'activo' => $Estado,
            'updated_at' => date('Y-m-d h:m:s'),
        ]);
        return $response;
    }
}
