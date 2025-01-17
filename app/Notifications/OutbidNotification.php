<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OutbidNotification extends Notification
{
    use Queueable;

    protected $product;
    protected $newBidAmount;

    public function __construct($product, $newBidAmount)
    {
        $this->product = $product;
        $this->newBidAmount = $newBidAmount;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Save to database for later retrieval
    }

    public function toDatabase($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'message' => 'Your bid for ' . $this->product->product_name . ' has been outbid by ' . $this->newBidAmount . '.',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Outbid Notification')
            ->line('Your bid for ' . $this->product->product_name . ' has been outbid by ' . $this->newBidAmount . '.',)
            ->action('View Details', url('/bidder/show'))
            ->line('Thank you for using our application!');
    }
}
