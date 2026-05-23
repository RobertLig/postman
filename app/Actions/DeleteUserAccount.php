<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DeleteUserAccount
{
    public function handle(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->delete();
        });

        Auth::logout();

        Session::invalidate();

        Session::regenerateToken();
    }
}
