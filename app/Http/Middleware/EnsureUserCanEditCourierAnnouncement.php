<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CourierAnnouncement;
use Illuminate\Support\Facades\Auth;

class EnsureUserCanEditCourierAnnouncement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $courierAnnouncement = CourierAnnouncement::findOrFail($request->route('courierannouncement'));

        if(Auth::user()->id !== $courierAnnouncement->user_id) 
        {
            abort(403);
        }

        return $next($request);
    }
}
