<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class Reloan extends Model {
    public $timestamps = false;
    protected $table = "tbl_reloan";

    public function Clientes()
    {
        return $this->hasOne(Clientes::class, 'id_clientes','id_clientes');
    }

}
