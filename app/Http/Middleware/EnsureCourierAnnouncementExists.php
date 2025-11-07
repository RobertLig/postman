<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Courier;

class EnsureCourierAnnouncementExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //$request->route('courier') is inconsistend. Once returns model, another time model's id

        /*dd($request->route('courier'));

        Courier::findOrFail($request->route('courier')); */

        return $next($request);
    }
}
