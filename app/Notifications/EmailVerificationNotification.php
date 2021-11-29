<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\Notifications\VerifyEmail;

class EmailVerificationNotification extends VerifyEmail
{
    use Queueable;

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
        $expirationDate = Carbon::now()
            ->addMinutes(Config::get('auth.verification.expire', 60));

        $url = URL::temporarySignedRoute(
            'verification.verify',
            $expirationDate,
            [
                'id' => $id,
                'hash' => $hash,
            ]
        );

        $apiUrl = env('APP_HOST') . ":8000/api";
        $reactAppUrl = env('REACT_APP_HOST') . '/auth';
        $emailVerificationUrl = str_replace($apiUrl, $reactAppUrl, $url);

        return $emailVerificationUrl;
    }
}