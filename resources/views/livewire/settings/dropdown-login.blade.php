<div>
    <x-dropdown>
        <x-slot:trigger>
            <x-button class="btn-ghost px-1 h-13">
                <x-avatar :image="auth()->user()->avatar" placeholder="{{ auth()->user()->initials() }}" class="!w-10">
                    <x-slot:title>
                        <x-icon name="o-chevron-down" class="w-4 h-4 -ms-2" />
                    </x-slot:title>
                </x-avatar>
            </x-button>
        </x-slot:trigger>

        <x-list-item :item="auth()->user()"  value="name" sub-value="email" no-separator no-hover class="pt-2" />
                    
        <x-menu-separator />

        <x-menu-sub title="{{ __('Settings') }}" icon="o-cog-6-tooth">
            <x-menu-item title="{{ __('Profile') }}" icon="o-user" link="{{ route('settings.profile') }}" />
            <x-menu-item title="{{ __('Password') }}" icon="o-lock-closed" link="{{ route('settings.password') }}" /> 
        </x-menu-sub>

        <x-menu-separator />

        <livewire:auth.logout />
    </x-dropdown>
</div>
