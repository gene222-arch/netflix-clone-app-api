<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionDueDateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Carbon|string $expirationDate;
    public int $daysBeforeExpiration;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Carbon|string $expirationDate, int $daysBeforeExpiration)
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
        $isExpirationDateString = gettype($this->expirationDate) === 'string';

        $expirationDate = $isExpirationDateString 
            ? $this->expirationDate 
            : $this->expirationDate->format('M d, Y');

        $expirationDateStatement = $isExpirationDateString 
            ? ''
            : 'Your account will expired at exactly ' . $expirationDate . '.';

        $greeting = !$this->daysBeforeExpiration 
            ? 'Your subscription will expire in ' . $this->expirationDate . ' hour/s'
            : 'Your subscription will expire in ' . $this->daysBeforeExpiration . ' days/s';

        return (new MailMessage)
                    ->subject('Subscription Expiration')
                    ->greeting($greeting)
                    ->line('Hi ' . $notifiable->first_name . ',')
                    ->line($expirationDateStatement)
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
