<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;


class RequestsCredit extends Model
{
    public $timestamps = false;
    protected $table = "tbl_requests_credit";    
    protected $primaryKey = 'id_req';
    protected $fillable = [
        'req_start_date', 'visit_day', 'promoter', 'first_name', 'last_name', 'phone',
        'num_cedula', 'id_department', 'id_zone', 'client_address', 'monto', 'plazo',
        'interes_porcent', 'num_cuotas', 'total', 'cuota', 'saldo', 'interes_valor',
        'intereses_por_cuota','activo','created_by','Origen'
    ];

    public function getZona()
    {
        return $this->hasOne(Zonas::class, 'id_zona','id_zone')->where('activo',1);
    }
    public function User()
    {
        return $this->hasOne(Usuario::class, 'id','created_by');
    }

    public static function getRequestsCredit(Request $request)
    {
    
        $IdZna          = $request->input('IdZna');
        $TypeForm       = $request->input('tyForm');

        $array_prospectos   = array();

        $Prospectos = RequestsCredit::where('activo', 1)
            ->when($TypeForm == 'RENOVAR', function ($q) {
                $q->where('Origen', 'Renovacion');
            })
            ->when($TypeForm != 'RENOVAR', function ($q) {
                $q->where(function ($q2) {
                    $q2->where('Origen', 'Nueva')->orWhere('Origen', 'Prospecto');
                });
            })
            ->when($IdZna > 0, function ($q) use ($IdZna) {
                $q->where('id_zone', $IdZna);
            })
            ->get();

        
        
        foreach ($Prospectos as $key => $c) {

            $array_prospectos[$key] = [
                "id_clientes"           => $c->id_req,
                "Nombre"                => strtoupper($c->first_name),
                "apellido"              => strtoupper($c->last_name),
                "Direccion"             => $c->client_address,
                "Cedula"                => $c->num_cedula,
                "Telefono"              => $c->phone,
                "Monto_promedio"        => $c->monto,
                "Zona"                  => $c->getZona->nombre_zona,
                "Usuario"               => $c->User->nombre,
                "Fecha_registro"        => date('Y-m-d', strtotime($c->req_start_date)),
                "Estado"                => '<span class="badge badge-warning"> <i class="far fa-clock"></i> '.$c->Origen.'</span>',
                "Accion"                => '<div class="btn-group w-100">
                                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                                                    <i class="fas fa-folder"></i>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a href="PagareALaOrden" target="_blank" class="dropdown-item">Pagare A La Orden</a>
                                                    <a href="SolicitudCredito" target="_blank"  class="dropdown-item">Solicitud de Credito</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="Pagare" target="_blank"  class="dropdown-item">Pagare</a>
                                                </div>
                                            </div>',
                "Botones"               => '<a href="Formulario/' . $c->id_req.' " class="btn btn-primary btn-block" ><i class="fas fa-edit"></i> </a>'
            ];
                
        }


        return $array_prospectos;
    }

    public static function UpdateEstadoRequest($IdProspecto)
    {        
        $response = RequestsCredit::where('id_req', $IdProspecto)->delete();
        return $response;
    }


}
