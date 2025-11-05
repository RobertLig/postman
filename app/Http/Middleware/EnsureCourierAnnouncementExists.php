<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CourierAnnouncement;

class EnsureCourierAnnouncementExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $courierAnnouncement = CourierAnnouncement::findOrFail($request->route('courierannouncement'));

        //dd($courierAnnouncement);

        if(!$courierAnnouncement) 
        {
            abort(404);
        }

        return $next($request);
    }
}
