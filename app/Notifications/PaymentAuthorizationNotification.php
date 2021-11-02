<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentAuthorizationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $checkOutUrl;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $checkOutUrl)
    {
        $this->checkOutUrl = $checkOutUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
                    ->greeting('Payment Authorization')
                    ->line('Click the button below for authorizing your payment and enjoy')
                    ->line('the unlimited streaming of movies our application offers.')
                    ->action('Authorize Payment', url($this->checkOutUrl))
                    ->line('Thank you for your patronage!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'PaymentAuthorizationNotification',
            'data' => [
                'user_id' => $notifiable->id,
                'status' => 'pending',
                'message' => 'Payment Authorization will expire in an hour, please authorize your payment within the specified time.'
            ]
        ];
    }
}
