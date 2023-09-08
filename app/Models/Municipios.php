<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipios extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "dbo.Cat_Municipio";

    public function getDepartamentos()
    {
        return $this->hasOne(Departamentos::class, 'id_departamento','id_departamento');
    }

    public static function getMunicipios()
    {
        return Municipios::where('activo',1)->get();
    }
}
