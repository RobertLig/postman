<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LogoutController extends Controller
{
    public function logout(Request $request): RedirectResponse
    {
        //Auth::guard('web')->logout();

        Auth::logout();
 
        $request->session()->invalidate();
 
        $request->session()->regenerateToken();
 
        return redirect(LaravelLocalization::localizeUrl('/'));
    }
}
