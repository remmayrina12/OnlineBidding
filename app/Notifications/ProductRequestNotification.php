<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Notifications\Notification;

class ProductRequestNotification extends Notification
{
    use Queueable;

    private $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['database'];  // Ensure 'sms' is in the via list
    }

    public function toDatabase($notifiable)
    {
        return [
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'user_id' => $this->product->user_id,
            'message' => $this->product->product_name . ' product has been submitted for approval.'
        ];
    }
}
