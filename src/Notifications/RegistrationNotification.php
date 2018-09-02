<?php

namespace Bitfumes\Multiauth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RegistrationNotification extends Notification
{
    use Queueable;

    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $prefix = ucfirst(config('multiauth.prefix'));

        return (new MailMessage())
            ->subject("New Registration for {$prefix}")
            ->line("You are registered as an {$prefix} with this Email. Now you can login with these credentials.")
            ->line("Your password is - {$this->password}")
            ->line('We suggest you to change this password as soon as possible.')
            ->action("Login as {$prefix}", route('admin.login'))
            ->line("If you don't know about this, just ignore this email");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
