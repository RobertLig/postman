<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public function logout(): void
    {
        Auth::guard('web')->logout();

        Session::invalidate();

        Session::regenerateToken();

        $this->success(__('Logged out successfully!'), position: 'toast-bottom', redirectTo: route('home'));
    }
}; ?>

<div>
    <x-menu-item wire:click="logout" title="{{ __('Log out') }}" icon="o-arrow-right-start-on-rectangle" />
</div>
