<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class UnSubscribedUserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpoPush($notifiable)
    {       
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title('Reminder: subscription update required.')
            ->body("Please update your flicklify subscription information.")
            ->setChannelId('update-subscription-channel')
            ->priority('high');
    }
}
