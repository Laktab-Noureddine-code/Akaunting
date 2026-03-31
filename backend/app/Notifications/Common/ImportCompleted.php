<?php
namespace App\Notifications\Common;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
class ImportCompleted extends Notification implements ShouldQueue
{
    use Queueable;
    protected $translation;
    protected $total_rows;
    public function __construct($translation, $total_rows)
    {
        $this->translation = $translation;
        $this->total_rows = $total_rows;
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
            ->subject(trans('notifications.import.completed.title'))
            ->line(new HtmlString('<br><br>'))
            ->line(trans('notifications.import.completed.description'))
            ->action(trans_choice('general.dashboards', 1), $dashboard_url);
    }
    public function toArray($notifiable)
    {
        return [
            'title' => trans('notifications.menu.import_completed.title'),
            'description' => trans('notifications.menu.import_completed.description', [
                'type'  => $this->translation,
                'count' => $this->total_rows,
            ]),
            'translation' => $this->translation,
            'total_rows' => $this->total_rows,
        ];
    }
}
