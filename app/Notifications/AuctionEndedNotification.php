<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuctionEndedNotification extends Notification
{
    protected $product;
    protected $type;

    public function __construct($product, $type)
    {
        $this->product = $product;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database']; // Use database notifications
    }

    public function toDatabase($notifiable)
    {
        $message = '';

        switch ($this->type) {
            case 'winner':
                $message = 'Congratulations! You won the auction for ' . $this->product->product_name . '.';
                break;
            case 'ended':
                $message = 'The auction for ' . $this->product->product_name . ' has ended.';
                break;
            case 'auctioneer':
                $message = 'The auction for your product ' . $this->product->product_name . ' has ended.';
                break;
        }

        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->product_name,
            'message' => $message,
        ];
    }
}
