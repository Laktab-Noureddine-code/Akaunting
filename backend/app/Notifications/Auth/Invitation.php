<?php
namespace App\Notifications\Auth;
use App\Models\Auth\UserInvitation;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
class Invitation extends Notification
{
    public $invitation;
    public function __construct($invitation)
    {
        $this->invitation = $invitation;
    }
    public function via($notifiable)
    {
        return ['mail'];
    }
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->line(trans('auth.invitation.message_1'))
            ->action(trans('auth.invitation.button'), route('register', $this->invitation->token))
            ->line(trans('auth.invitation.message_2'));
    }
}
