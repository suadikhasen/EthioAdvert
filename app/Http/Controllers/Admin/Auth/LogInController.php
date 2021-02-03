<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogInController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(AdminRequest $adminRequest)
    {
        $credentials = $adminRequest->only('email', 'password');
        if(Auth::guard('admin')->attempt($credentials)){
            $adminRequest->session()->regenerate();
            return redirect()->route('admin.home');
        }
        return back()->with('error_notification','invalid credentials please try again');
    }

    public function logOut(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin_login')->with('success_notification','sign out successfully');
    }
}
