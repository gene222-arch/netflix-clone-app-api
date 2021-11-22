<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class SubscriptionExpiredExpoNotification extends Notification
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
            ->title('⏳ Subscription Expired')
            ->body("Please renew your subscription")
            ->setChannelId('subscription-expired-channel')
            ->priority('normal');
    }
}
