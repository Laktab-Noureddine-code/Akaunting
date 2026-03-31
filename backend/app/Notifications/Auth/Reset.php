<?php
namespace App\Notifications\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
class Reset extends Notification
{
    public $token;
    public $email;
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }
    public function via($notifiable)
    {
        return ['mail'];
    }
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->line(trans('auth.notification.message_1'))
            ->action(trans('auth.notification.button'), route('reset', ['token' => $this->token, 'email' => $this->email]))
            ->line(trans('auth.notification.message_2'));
    }
}
