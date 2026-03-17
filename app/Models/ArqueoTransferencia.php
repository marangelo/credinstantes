<?php

namespace App\Models;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArqueoTransferencia extends Model
{
    public $timestamps = false;
    protected $table = "tbl_arqueo_transferencia";
    protected $primaryKey = 'id_tranferencia';
    public function BancoCuentas() 
    {
        return $this->hasOne(BancoCuentas::class, 'id_cuenta', 'id_cuenta');
    }
}
