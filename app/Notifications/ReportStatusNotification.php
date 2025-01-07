<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusNotification extends Notification
{
    use Queueable;

    private $report;
    private $status;

    public function __construct($report, $status)
    {
        $this->report = $report;
        $this->status = $status;
    }

    public function via(object $notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable)
    {
        return [
            'report_id' => $this->report->report_id,
            'reported_by' => $this->report->reported_by, // Handle null safely
            'reported_user_id' => $this->report->reported_user_id,
            'reason' => $this->report->reason,
            'message' => $this->status === 'reviewed'
                                ? 'Your report has been reviewed by admin.'
                                : 'Your report is pending',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Report Status')
            ->line($this->status === 'reviewed'
                        ? 'Your report has been reviewed by admin.'
                        : 'Your report is pending')
            ->action('View Details', url('/home'))
            ->line('Thank you for using our application!');
    }
}
