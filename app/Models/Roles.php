<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class Roles extends Model {
    protected $table = "rol";

    public static function getRoles()
    {
        return Roles::where('activo','S')->get();
    }
}
