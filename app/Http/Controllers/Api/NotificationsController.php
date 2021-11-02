<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function findCurrentPaymentAuthorizationByUserId()
    {
        $userId = request()->input('userId');

        $notification = Notification::query()
            ->where([
                [ 'type', '=', 'App\Notifications\PasswordResetNotification' ],
                [ 'notifiable_id', '=', $userId ],
                [ 'read_at', '=', NULL ]
            ])
            ->get()
            ->last();

        return $this->success($notification);
    }
}
