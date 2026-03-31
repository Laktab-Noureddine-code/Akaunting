<?php
namespace App\Notifications\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
class InvalidEmail extends Notification implements ShouldQueue
{
    use Queueable;
    protected string $email;
    protected string $type;
    protected string $error;
    public function __construct(string $email, string $type, string $error)
    {
        $this->email = $email;
        $this->type = $type;
        $this->error = $error;
        $this->onQueue('notifications');
    }
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    public function toMail($notifiable): MailMessage
    {
        $dashboard_url = route('dashboard', ['company_id' => company_id()]);
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(trans('notifications.email.invalid.title', ['type' => $this->type]))
            ->line(new HtmlString('<br><br>'))
            ->line(trans('notifications.email.invalid.description', ['email' => $this->email]))
            ->line(new HtmlString('<br><br>'))
            ->line(new HtmlString('<i>' . $this->error . '</i>'))
            ->action(trans_choice('general.dashboards', 1), $dashboard_url);
    }
    public function toArray($notifiable)
    {
        return [
            'title' => trans('notifications.menu.invalid_email.title', ['type' => $this->type]),
            'description' => trans('notifications.menu.invalid_email.description', ['email' => $this->email]),
            'email' => $this->email,
        ];
    }
}
