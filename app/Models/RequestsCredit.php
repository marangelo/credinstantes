<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

use Auth;


class RequestsCredit extends Model
{
    const PROMOTOR = 2;
    const ADMIN = 1;
    const OPERACIONES = 3;

    public $timestamps = false;
    protected $table = "tbl_requests_credit";    
    protected $primaryKey = 'id_req';
    protected $fillable = [
        'req_start_date', 'visit_day', 'promoter', 'first_name', 'last_name', 'phone',
        'num_cedula', 'id_department', 'id_zone', 'client_address', 'monto', 'plazo',
        'interes_porcent', 'num_cuotas', 'total', 'cuota', 'saldo', 'interes_valor',
        'intereses_por_cuota','activo','created_by','Origen','id_cliente'
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
        $IdZna    = $request->input('IdZna');
        $TypeForm = $request->input('tyForm');
        $UserAuth = Auth::user();

        if ($UserAuth->id_rol == self::PROMOTOR) {
            $IdZna = $UserAuth->id_zona;
        }

        $query = RequestsCredit::where('activo', 1)
            ->with('getZona', 'User')
            ->when($IdZna > 0, fn($q) => $q->where('id_zone', $IdZna));

        if ($TypeForm == 'RENOVAR') {
            $query->where('Origen', 'Renovacion');
        } else {
            $query->whereIn('Origen', ['Nueva', 'Prospecto']);
        }

        if (!in_array($UserAuth->id_rol, [self::ADMIN, self::OPERACIONES])) {
            $query->where('created_by', $UserAuth->id);
        }

        return $query->get()->map(function ($c) {
            return [
                "id_clientes"    => $c->id_req,
                "Nombre"         => strtoupper($c->first_name),
                "apellido"       => strtoupper($c->last_name),
                "Direccion"      => $c->client_address,
                "Cedula"         => $c->num_cedula,
                "Telefono"       => $c->phone,
                "Monto_promedio" => $c->monto,
                "Zona"           => $c->getZona->nombre_zona ?? '',
                "Usuario"        => $c->User->nombre ?? '',
                "Fecha_registro" => date('Y-m-d', strtotime($c->req_start_date)),
                "Estado" => sprintf(
                    '<span class="badge badge-warning"><i class="far fa-clock"></i> %s</span>',
                    $c->Origen
                ),
                "Accion" => '<div class="btn-group w-100">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                        <i class="fas fa-folder"></i>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a href="PagareALaOrden" target="_blank" class="dropdown-item">Pagare A La Orden</a>
                        <a href="SolicitudCredito" target="_blank" class="dropdown-item">Solicitud de Credito</a>
                        <div class="dropdown-divider"></div>
                        <a href="Pagare" target="_blank" class="dropdown-item">Pagare</a>
                    </div>
                </div>',
                "Botones" => sprintf(
                    '<a href="Formulario/%s" class="btn btn-primary btn-block"><i class="fas fa-edit"></i></a>',
                    $c->id_req
                ),
            ];
        })->toArray();
    }


    
    public function getNegocio()
    {
        return $this->hasOne(ClientesNegocio::class, 'id_req', 'id_req');
    }

    public function getConyugue()
    {
        return $this->hasOne(ClientesConyugue::class, 'id_req', 'id_req');
    }
    public function getFiador()
    {
        return $this->hasOne(ClientesFiador::class, 'id_req', 'id_req');
    }
    public function getGarantias()
    {
        return $this->hasMany(ClientesGarantia::class, 'id_req', 'id_req');
    }    
    public function getReferencias()
    {
        return $this->hasMany(ClientesReferencias::class, 'id_req', 'id_req');
    } 


    public static function UpdateEstadoRequest($IdProspecto)
    {        
        //$response = RequestsCredit::where('id_req', $IdProspecto)->delete();

        $response = RequestsCredit::where('id_req', $IdProspecto)->update([
            'activo' => 2
        ]);
        return $response;
    }
    public static function UpRequest($IdProspecto)
    {        
        $response = RequestsCredit::where('id_req', $IdProspecto)->update([
            'activo' => 0
        ]);
        
        return $response;
    }


}
