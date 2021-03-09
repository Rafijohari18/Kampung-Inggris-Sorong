<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function loginBackend($request)
    {
        $remember = $request->has('remember') ? true : false;
        if (Auth::attempt($request->forms(), $remember)) {

            return true;
        } else {
            return false;
        }
    }

    public function logoutBackend()
    {
        Auth::logout();

        return true;
    }
}
