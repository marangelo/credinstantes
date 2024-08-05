<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class ProxVencer extends Model {
    public $timestamps = false;
    protected $table = "view_Credito_Proximo_Vencer";

    public static function ProxVencer()
    {

    }

}
