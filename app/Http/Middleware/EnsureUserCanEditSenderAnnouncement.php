<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Sender;
//use App\Models\User; //can't resolve user from service container, why?
use Illuminate\Support\Facades\Auth;

class EnsureUserCanEditSenderAnnouncement
{
    //public User $user;

    //public Sender $senderannouncement;

    /*public function __construct(User $user, Sender $senderannouncement)
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
    public function handle(Request $request, Closure $next/*, Sender $senderannouncement*/): Response //route model binding doesn't work for $senderannouncement
    {

        //dd($request->route('senderannouncement'));

        $sender = Sender::findOrFail($request->route('sender')); //{senderannouncement} placeholder from uri

        if (Auth::user()->id !== $sender->user_id) //$senderannouncement->user_id
        {
            abort(403);
        }

        return $next($request);
    }
}
