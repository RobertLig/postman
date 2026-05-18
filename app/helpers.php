<?php

if (!function_exists('consent')) {

    function consent(string $key): bool
    {
        $cookie = request()->cookie('cookie_consent');

        if (!$cookie) {
            return false;
        }

        $consents = json_decode($cookie, true);

        if (!is_array($consents)) {
            return false;
        }

        return (bool) ($consents[$key] ?? false);
    }
}
