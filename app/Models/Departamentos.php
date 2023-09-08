<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamentos extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "dbo.Cat_Departamento";

    public static function getDepartamentos()
    {
        return Departamentos::all();
    }
}
