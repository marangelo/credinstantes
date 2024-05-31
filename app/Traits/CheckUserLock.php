<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

trait CheckUserLock
{
    public function checkUserLock()
    {

        if (Auth::User()->Lock == 0) {
            auth()->logout();
            return redirect('/');
        }
        return null; 
    }
}
