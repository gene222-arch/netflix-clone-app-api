<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class EmployeeEmailVerification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $password = '';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $password)
    {
        $this->password = $password;
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
        $hashedEmail = sha1($notifiable->email);
        $id = $notifiable->id;

        $urlVerificationPath =  env('REACT_APP_URL') . "/employees/email/verify?id=$id&hash=$hashedEmail";

        return (new MailMessage)
                    ->subject('Email Verification')
                    ->greeting('Hello Sir/Maam, ' . $notifiable->first_name)
                    ->line('')
                    ->line('The following will be your permanent ' . env('APP_NAME') . ' account.')
                    ->line('Upon opening your account please do change your password.')
                    ->line(new HtmlString('<strong>Email: </strong>' . $notifiable->email))
                    ->line(new HtmlString('<strong>Password: </strong>' . $this->password))
                    ->line('')
                    ->action('Verify', url($urlVerificationPath));
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
