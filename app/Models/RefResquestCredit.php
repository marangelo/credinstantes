<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

class RefResquestCredit extends Model {
    public $timestamps = false;
    protected $table = "tbl_ref_request_credit";
    protected $primaryKey = 'id_ref';

    protected $fillable = [
        'id_ref','id_resquest','id_credit'
    ];

    public function getRequest()
    {
        return $this->hasOne(RequestsCredit::class, 'id_req','id_resquest');
    }

    public function getCredit()
    {
        return $this->hasOne(RequestsCredit::class, 'id_req','id_credit');
    }


}
