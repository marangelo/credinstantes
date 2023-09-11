<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Credito extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Tbl_Creditos";    
    protected $primaryKey = 'id_creditos';

    public function abonos()
    {
        return $this->hasMany(Abono::class, 'id_creditos', 'id_creditos')->orderBy('id_abonoscreditos', 'desc')->limit(1);
    }


    public static function getCreditos()
    {
        return Credito::all();
    }
}
