<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class NotSubscribed implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::query()->firstWhere('email', $value);

        if (! $user) return false; 

        $subscriptionDetails = $user->currentSubscription();

        if ($subscriptionDetails->is_expired || 
            !$subscriptionDetails->subscribed_at ||
            !$subscriptionDetails->expired_at || 
            $subscriptionDetails->is_cancelled
        ) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'User account has already subscribed.';
    }
}
