<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $table = 'login_log';

    protected $fillable = [
        'user_id',
        'ip',
        'browser',
        'browser_version',
        'platform',
        'device',
        'device_model',
        'device_brand',
        'android_id',
    ];

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }
}
