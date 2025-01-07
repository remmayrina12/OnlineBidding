<?php

namespace App\Notifications;

use Twilio\Rest\Client;
use Illuminate\Bus\Queueable;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProductRequestNotification extends Notification
{
    use Queueable;

    private $product;

    /**
     * Create a new notification instance.
     *
     * @param $product
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Determine the delivery channels for the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // 'twilio' is a custom channel
    }

    /**
     * Store the notification data in the database.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'user_id' => $this->product->user_id,
            'message' => $this->product->product_name . ' product has been submitted for approval.',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Product Request')
            ->line($this->product->product_name . ' product has been submitted for approval.')
            ->action('View Details', url('/admin/manageProduct'))
            ->line('Thank you for using our application!');
    }

}
