<?php

namespace App\Models;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BancoCuentas extends Model
{
    public $timestamps = false;
    protected $table = "tbl_bancos_cuentas";
    protected $primaryKey = 'id_cuenta';

    public function Banco()
    {
        return $this->hasOne(Banco::class, 'id_bancos','id_banco');
    }

    public static function getBancoCuentas()    
    {

        $Cuentas =  BancoCuentas::where('activo',1)->get();

        $array_cuentas = array();
        
        foreach ($Cuentas as $key => $c) {  

            $array_cuentas[$key] = [
                "id_cuenta"     => $c->id_cuenta,
                "Descripcion"   => $c->Banco->banco.' '.$c->moneda.' '.$c->cuenta.'',
                "banco"         => $c->Banco->banco,
                "moneda"        => $c->moneda,
                "cuenta"        => $c->cuenta,
            ];
                
        }
        return $array_cuentas;
    }
}
