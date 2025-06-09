<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class ClientesFiador extends Model
{
    //public $timestamps = false;
    protected $table = "tbl_clientes_fiador";
    protected $primaryKey = 'id_fiador';

    protected $fillable = [
        'id_fiador','id_cliente','id_req','nombre_fiador','apellidos_fiador','Cedula_fiador','estado_civil_fiador','Telefono_fiador','dir_domicilio_fiador','dir_trabajo_fiador',
    ];
}
