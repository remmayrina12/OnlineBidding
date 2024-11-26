<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportReviewNotification extends Notification
{
    use Queueable;
    private $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'report_id' => $this->report->report_id,
            'reported_by' => $this->report->reported_by,
            'reported_user_id' => $this->report->reported_user_id,
            'reason' => $this->report->reason,
            'status' => $this->report->status,
            'message' => 'Someone has been reported, check the reports.'
        ];
    }
}