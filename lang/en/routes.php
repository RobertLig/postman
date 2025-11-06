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
    "senders-announcements" => "senders-announcements",
    "senders-announcements-create" => "senders-announcements/create", //senders-announcements/create
    "senders-announcements-edit" => "senders-announcements/{senderannouncement}/edit", #try {edit} for Translatable route parameters (with database)
    "senders-announcements-show" => "senders-announcements/{senderannouncement}",
    "couriers-announcements-create" => "couriers-announcements/create",
    "couriers-announcements" => "couriers-announcements",
    "couriers-announcements-show" => "couriers-announcements/{courier}",
    "couriers-announcements-edit" => "couriers-announcements/{courier}/edit",
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