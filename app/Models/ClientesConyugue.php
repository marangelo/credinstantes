<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class ClientesConyugue extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "tbl_clientes_info_conyuge";
    protected $primaryKey = 'id_conyuge';

    protected $fillable = [
        'id_conyuge','id_cliente','nombres','apellidos','no_cedula','telefono','direccion_trabajo'
    ];
}