<?php

namespace App\Models;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Banco extends Model
{
    public $timestamps = false;
    protected $table = "tbl_bancos";
    protected $primaryKey = 'id_bancos';
}
