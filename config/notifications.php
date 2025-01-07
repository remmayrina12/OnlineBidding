<?php

return [

    'channels' => [
        'mail' => \Illuminate\Notifications\Channels\MailChannel::class,
        'database' => \Illuminate\Notifications\Channels\DatabaseChannel::class,
        'twilio' => \NotificationChannels\Twilio\TwilioChannel::class, // Add Twilio channel here
    ],

];
