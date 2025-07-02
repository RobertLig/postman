
<div {{ $attributes->class(['']) }}>
    @for ($i = 0; $i < 5; $i++)
        {{ $slot }}
    @endfor
</div>