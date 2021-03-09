<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginBackendRequest;
use App\Services\Auth\LoginService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $service;

    use ValidatesRequests;

    public function __construct(
        LoginService $service
    )
    {
        $this->service = $service;
    }

    public function showLoginBackendForm(Request $request)
    {
        if ($request->segment(2) == 're-authentication') {
            return redirect()->route('backend.login')
                ->with('warning', 'Session expired, please login again');
        }

        return view('auth.login-backend', [
            'title' => 'Authentication required'
        ]);
    }

    public function loginBackend(LoginBackendRequest $request)
    {
        $login = $this->service->loginBackend($request);

        if ($login == true) {
            return redirect()->route('backend.dashboard')->with('success', 'Login succesfully');
        } else {
            return back()->with('failed', 'Username / Password wrong !');
        }
    }

    public function logoutBackend()
    {
        $this->service->logoutBackend();

        return redirect()->route('backend.login')->with('success', 'Logout successfully');
    }
}
