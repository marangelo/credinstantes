<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientesReactivacion extends Model
{
    public $timestamps = false;
    protected $table = "tbl_clientes_reactivacion";
    protected $primaryKey = 'id_reactivacion';

    protected $fillable = [
        'id_reactivacion','id_clientes','fecha_reactivacion','user_created'
    ];

    public function Clientes()
    {
        return $this->hasOne(Clientes::class, 'id_clientes','id_clientes');
    }
}
