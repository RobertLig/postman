<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\SenderAnnouncement;
//use App\Models\User; //can't resolve user from service container, why?
use Illuminate\Support\Facades\Auth;

class EnsureUserCanEditSenderAnnouncement
{
    //public User $user;

    //public SenderAnnouncement $senderannouncement;

    /*public function __construct(User $user, SenderAnnouncement $senderannouncement)
    {
        dd($user->id);
        //$this->user = $user;

        $this->senderannouncement = $senderannouncement;
    }*/

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next/*, SenderAnnouncement $senderannouncement*/): Response //route model binding doesn't work for $senderannouncement
    {
        
        //dd($request->route('senderannouncement'));

        $senderAnnouncement = SenderAnnouncement::findOrFail($request->route('senderannouncement')); //{senderannouncement} placeholder from uri

        if(Auth::user()->id !== $senderAnnouncement->user_id) //$senderannouncement->user_id
        {
            abort(403);
        }

        return $next($request);
    }
}
