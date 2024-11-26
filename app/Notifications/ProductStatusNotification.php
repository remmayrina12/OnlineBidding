<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductStatusNotification extends Notification
{
    use Queueable;

    private $product;
    private $product_post_status;

    public function __construct($product, $product_post_status)
    {
        $this->product = $product;
        $this->product_post_status = $product_post_status;
    }

    public function via($notifiable)
    {
        return ['database']; // Storing in the database
    }

    public function toDatabase($notifiable)
    {
        return [
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_post_status' => $this->product->product_post_status,
            'message' => $this->product_post_status === 'active'
                ? 'Your product ' . $this->product->product_name . ' has been approved and is now active.'
                : 'Your product ' . $this->product->product_name . ' has been rejected.',
        ];
    }
}
