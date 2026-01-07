<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArqueoDesembolso extends Model
{
    public $timestamps = false;
    protected $table = "tbl_arqueo_desembolso";
    protected $primaryKey = 'id_desembolsos';
    public function getCliente() 
    {
        return $this->hasOne(Clientes::class, 'id_clientes', 'id_cliente');
    }
}
