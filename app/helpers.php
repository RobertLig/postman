<?php

if (!function_exists('consent')) {

    function consent(string $key): bool
    {
        return request()->cookie($key . '_consent') === 'true';
    }
}
