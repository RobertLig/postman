<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Notifications\Messages\MailMessage;
//use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

class QueueableVerifyEmail extends VerifyEmail implements ShouldQueue // extends Notification
{
    use Queueable;
}
