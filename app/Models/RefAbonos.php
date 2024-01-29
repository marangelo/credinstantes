<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class RefAbonos extends Model {
    public $timestamps = false;
    protected $table = "tbl_pagosabonos";    
    protected $primaryKey = 'id_pagoabono';

    public function Creditos()
    {
        return $this->hasMany(Credito::class, 'id_creditos', 'id_creditos');
    }

}