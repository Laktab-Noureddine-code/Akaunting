<?php
namespace App\Notifications\Common;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
class ExportFailed extends Notification implements ShouldQueue
{
    use Queueable;
    public $message;
    public function __construct($message)
    {
        $this->message = $message;
        $this->onQueue('notifications');
    }
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(trans('notifications.export.failed.title'))
            ->line(new HtmlString('<br><br>'))
            ->line(trans('notifications.export.failed.description'))
            ->line(new HtmlString('<br><br>'))
            ->line($this->message)
            ->line(new HtmlString('<br><br>'));
    }
    public function toArray($notifiable)
    {
        return [
            'title' => trans('notifications.menu.export_failed.title'),
            'description' => trans('notifications.menu.export_failed.description'),
            'message' => $this->message,
        ];
    }
}
