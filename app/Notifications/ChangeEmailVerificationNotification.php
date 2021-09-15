<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeEmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $delays = 1;
    public ?int $verificationCode = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $verificationCode)
    {
        $this->verificationCode = $verificationCode;
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
                    ->subject('Change Email Verification')
                    ->greeting('Hi there, ' . $notifiable->first_name)
                    ->line('Below is the verification code for updating your Flicklify Account email address.')
                    ->line('Code: ' . $this->verificationCode)
                    ->line('Thank you for using our application!');
    }
}
