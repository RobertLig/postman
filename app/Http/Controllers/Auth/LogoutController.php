<?php
//this controller is not used in application
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LogoutController extends Controller 
{
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        //Auth::logout();
 
        $request->session()->invalidate();
 
        $request->session()->regenerateToken();
 
        return redirect(LaravelLocalization::localizeUrl('/'));
    }
}
