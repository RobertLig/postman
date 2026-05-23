<?php

namespace App\Livewire\Settings;

use App\Actions\DeleteUserAccount as DeleteUserAccountAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

class DeleteAccount extends Component
{
    use Toast;

    public function deleteAccount(
        DeleteUserAccountAction $deleteUserAccount
    ): void {

        $deleteUserAccount->handle(Auth::user());

        $this->success(
            __('Account deleted'),
            position: 'toast-bottom',
            redirectTo: route('home')
        );
    }

    public function render()
    {
        return view('livewire.settings.delete-account');
    }
}
