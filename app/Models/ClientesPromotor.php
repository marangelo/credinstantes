<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClientesPromotor extends Model
{
    public $timestamps = false;
    protected $table = "view_cliente_master";
  
    public function Municipio()
    {
        return $this->belongsTo(Municipios::class, 'ID_MUNICIPIO','id_municipio');
    }
    public function Zona()
    {
        return $this->belongsTo(Zonas::class, 'ID_ZONA','id_zona');
    }
}