<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User', function () {
    return true;
});

Broadcast::channel('movie.created', function () {
    return true;
});

Broadcast::channel('coming.soon.movie.created', function () {
    return true;
});

Broadcast::channel('coming.soon.movie.released', function () {
    return true;
});

Broadcast::channel('user.profile.manage.pincode.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId;
});

Broadcast::channel('subscribed.successfully', function () {
    return true;
});

