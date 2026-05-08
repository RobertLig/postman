<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Sender;

class EnsureSenderAnnouncementExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sender = Sender::findOrFail($request->route('sender'));

        //dd($sender);

        if (!$sender) {
            abort(404);
        }

        return $next($request);
    }
}
