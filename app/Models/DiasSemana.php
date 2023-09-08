<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiasSemana extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "dbo.Cat_DiasSemana";

    public static function getDiasSemana()
    {
        return DiasSemana::where('activo',1)->get();
    }
}
