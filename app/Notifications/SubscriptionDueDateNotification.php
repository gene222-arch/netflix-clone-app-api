<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionDueDateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Carbon $expirationDate;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Carbon $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Good day our fellow subscribers,')
                    ->subject('Subscription Expiration')
                    ->line('Your account will expire in a week at exactly')
                    ->line($this->expirationDate)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
