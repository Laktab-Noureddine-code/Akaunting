<?php
namespace App\Notifications\Common;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
class ImportFailed extends Notification implements ShouldQueue
{
    use Queueable;
    public $errors;
    public function __construct($errors)
    {
        $this->errors = $errors;
        $this->onQueue('notifications');
    }
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(trans('notifications.import.failed.title'))
            ->line(new HtmlString('<br><br>'))
            ->line(trans('notifications.import.failed.description'));
        foreach ($this->errors as $error) {
            $message->line(new HtmlString('<br><br>'));
            $message->line($error);
        }
        $message->line(new HtmlString('<br><br>'));
        return $message;
    }
    public function toArray($notifiable)
    {
        return [
            'title' => trans('notifications.menu.import_failed.title'),
            'description' => trans('notifications.menu.import_failed.description'),
            'errors' => $this->errors,
        ];
    }
}
