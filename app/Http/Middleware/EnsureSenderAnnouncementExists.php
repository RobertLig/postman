<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SenderAnnouncement;

class EnsureSenderAnnouncementExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $senderAnnouncement = SenderAnnouncement::findOrFail($request->route('senderannouncement'));

        //dd($senderAnnouncement);

        if(!$senderAnnouncement) 
        {
            abort(404);
        }

        return $next($request);
    }
}
