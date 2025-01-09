<?php

namespace App\Models;

use Auth;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class Solicitudes extends Model {
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
                "Estado"                => '<span class="badge badge-warning"> <i class="far fa-clock"></i> Pendiente</span>',
                "Accion"                => '<div class="btn-group w-100">
                                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                                                    <i class="fas fa-folder"></i>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a href="PagareALaOrden" target="_blank" class="dropdown-item">Pagare A La Orden</a>
                                                    <a href="SolicitudCredito" target="_blank"  class="dropdown-item">Solicitud de Credito</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="Pagare" target="_blank"  class="dropdown-item">Pagare</a>
                                                    <a href="#" class="dropdown-item">Documento 04</a>
                                                </div>
                                            </div>',
            ];
                
        }


        return $array_prospectos;
    }
    
}
