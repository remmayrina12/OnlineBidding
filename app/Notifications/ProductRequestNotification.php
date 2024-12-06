<?php

namespace App\Notifications;

use Vonage\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

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
        return ['database']; // Use database notifications
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

    // public function toSms($notifiable)
    // {
    //     $vonageClient = new Client(new Client\Credentials\Basic(
    //         config('services.vonage.api_key'),
    //         config('services.vonage.api_secret')
    //     ));

    //     $vonageClient->message()->send([
    //         'to' => $notifiable->routeNotificationForSms(),
    //         'from' => config('services.vonage.sms_from'),
    //         'text' => $this->product->product_name . ' product has been submitted for approval.',
    //     ]);
    // }
}
