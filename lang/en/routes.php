<?php

return [
    "confirm-password" => "confirm-password",
    "cookie-policy" => "cookie-policy",
    "register" => "register",
    "login" => "login",
    "messages" => "messages",
    "about"    =>  "about",
    "contact" => "contact",
    "faq" => "faq",
    "forgot-password" => "forgot-password",
    "privacy-policy" => "privacy-policy",
    "reset-password" => "reset-password/{token}",
    'senders' => 'senders',
    'senders-create' => 'senders/create',
    'senders-edit' => 'senders/{announcement}/edit',
    "senders-announcements-show" => "senders-announcements/{announcement}",
    'couriers-create' => 'couriers/create',
    'couriers' => 'couriers',
    "couriers-announcements-show" => "couriers-announcements/{announcement}",
    'couriers-edit' => 'couriers/{announcement}/edit',
    "settings" => "settings",
    "settings-password" => "settings/password",
    "settings-profile" => "settings/profile",
    "terms-of-use" => "terms-of-use",
    "users" => "users",
    "verify-email" => "verify-email",
    "verify-email-handler" => "verify-email/{id}/{hash}",
    "chat" => "chat/users/{user}", // can't use /senderannouncement/{senderannouncement}. system doesn't see senderannouncement. why?
    //"chat" => "chat/{senderannouncement}", //doesn't work
];
