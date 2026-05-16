<div>

    <x-menu-item title="{{ __('Messages') }}" icon="o-chat-bubble-left-right" link="{{ route('conversations.index') }}"
        :badge="$count > 0 ? $count : null" badge-classes="badge-error" @class([
            'bg-neutral text-neutral-content' => request()->routeIs('conversations.*'),
        ]) />

</div>
