<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class ClientesNegocio extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "tbl_clientes_negocios";
    protected $primaryKey = 'id_negocio';

    protected $fillable = [
        'id_negocio','id_cliente','id_req','nombre_negocio','antiguedad','direccion'
    ];
}