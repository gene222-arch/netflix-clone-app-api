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
    public int $daysBeforeExpiration;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Carbon $expirationDate, int $daysBeforeExpiration)
    {
        $this->expirationDate = $expirationDate;
        $this->daysBeforeExpiration = $daysBeforeExpiration;
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
                    ->subject('Subscription Expiration')
                    ->greeting('Your subscription will expire in ' . $this->daysBeforeExpiration . ' day/s')
                    ->line('Hi ' . $notifiable->first_name . ',')
                    ->line('Your account will at exactly ' . $this->expirationDate->format('M d, Y') . '.')
                    ->line('Please do update your subscription to further enjoy')
                    ->line('the services you`ve enjoyed so far.')
                    ->action('Renew Subscription', 'http://localhost:3000/renew-subscription')
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
