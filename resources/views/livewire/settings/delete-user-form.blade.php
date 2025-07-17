<?php

use Livewire\Volt\Component;
//use Livewire\Attributes\Validate;
//use App\Livewire\Actions\Logout; //doesn't exists such a class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
//use App\Models\User; //doesn't work
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    //#[Validate('required|current_password')]
    //public $password = '';

    /*public $user;

    public function mount(User $user) //doesn't work
    {
        $this->user = $user;
        //dd(Auth::user()->name);
    }*/

    public function deleteAccount(/*Logout $logout*/)
    {
        //$this->validate();

        //tap(Auth::user(), $logout(...))->delete();

        Auth::user()->delete();

        Auth::guard('web')->logout();
 
        Session::invalidate();
 
        Session::regenerateToken();

        $this->success(
            __('Account deleted'), 
            position: 'toast-bottom',
            redirectTo: route('home')
        );

        //dd($this->user->name);
    }
}; ?>

<div>
    <x-header subtitle="{{ __('Delete your account and all of its resources.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Delete account') }}
        </x-slot>

        <x-slot:actions>
            <x-button wire:click="deleteAccount" 
               wire:confirm="{{ __('Are you sure?') }}"
               label="{{ __('Delete account') }}" class="btn-error" spinner="deleteAccount" />
        </x-slot:actions>
    </x-header>

    {{-- <x-form wire:submit="deleteAccount" no-separator>
        <x-slot:actions>
            <x-button label="{{ __('Delete account') }}" class="btn-error" type="submit" spinner="deleteAccount" />
        </x-slot:actions>
    </x-form> --}}
</div>
