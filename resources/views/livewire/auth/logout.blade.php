<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mary\Traits\Toast;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

new class extends Component {
    use Toast;
    
    public function logout()
    {
        Auth::guard('web')->logout();

        //Auth::logout();
 
        Session::invalidate();
 
        Session::regenerateToken();
 
        $this->success(
            __('Logout successfully!'), 
            position: 'toast-bottom',
            redirectTo: LaravelLocalization::localizeUrl('/') 
        );
    }
}; ?>

<div>
    <x-form wire:submit="logout" no-separator>
        <x-slot:actions>
            <x-button icon="o-power" type="submit" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate /> <!-- link="/logout" --> 
        </x-slot:actions>
    </x-form>
</div>
