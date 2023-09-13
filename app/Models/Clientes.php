<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clientes extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Tbl_Clientes";
    protected $primaryKey = 'id_clientes';

    public function getMunicipio()
    {
        return $this->hasOne(Municipios::class, 'id_municipio','id_municipio');
    }

    public static function getClientes()
    {
        return Clientes::where('activo',1)->orderBy('id_clientes', 'asc')->where('activo',1)->get();
    }
    public function getCreditos()
    {
        return $this->hasMany(Credito::class, 'id_clientes','id_clientes')->where('activo',1);
    }

   
}
