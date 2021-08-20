<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerificationNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public $delays = 5;

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        $id = $notifiable->getKey();
        $hash = sha1($notifiable->getEmailForVerification());

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $id,
                'hash' => $hash,
            ]
        );

        $url = str_replace('8000/api', '3000', $url);
        
        return str_replace("verify-email/{$id}/{$hash}?", "verify-email?id={$id}&hash={$hash}&", $url);
    }
}