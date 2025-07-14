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
    <x-menu-item wire:click="logout" title="{{ __('Log out') }}" icon="o-arrow-right-start-on-rectangle" />
    
    {{-- <x-form wire:submit="logout" no-separator>
        <x-slot:actions> 
             <x-button label="Log out" icon="o-arrow-right-start-on-rectangle" type="submit" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate />
         </x-slot:actions> 
    </x-form> --}}
</div>
