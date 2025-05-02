<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientesReactivacion extends Model
{
    public $timestamps = false;
    protected $table = "tbl_clientes_reactivacion";
    protected $primaryKey = 'id_reactivacion';

    protected $fillable = [
        'id_reactivacion','id_clientes','fecha_reactivacion','user_created'
    ];
}
