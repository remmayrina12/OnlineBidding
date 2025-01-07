<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductNotifyToAllBidders extends Notification
{
    use Queueable;

    private $product;

    public function __construct($product)
    {
        $this->product = $product;
    }
    public function via(object $notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable)
    {
        return [
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'message' => 'Someone opened an auction go and check it out!'
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Product Notification')
            ->line('Someone opened an auction go and check it out!')
            ->action('View Details', url('/home'))
            ->line('Thank you for using our application!');
    }
}
