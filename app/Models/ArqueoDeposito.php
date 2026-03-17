<?php

namespace App\Models;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArqueoDeposito extends Model
{
    public $timestamps = false;
    protected $table = "tbl_arqueo_deposito";
    protected $primaryKey = 'id_desembolsos';
    public function Cliente() 
    {
        return $this->hasOne(Clientes::class, 'id_clientes', 'id_cliente');
    }
    public function Cuenta() 
    {
        return $this->hasOne(BancoCuentas::class, 'id_cuenta', 'id_cuenta');
    }
}
