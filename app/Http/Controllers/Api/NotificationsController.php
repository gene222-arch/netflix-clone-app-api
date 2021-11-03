<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentAuthorizationNotifications()
    {
        $notification = auth('api')
            ->user()
            ->notifications
            ->filter(function ($notification) {
                return $notification->type === 'App\Notifications\PaymentAuthorizationNotification';
            });

        return !$notification->count() ? $this->noContent() : $this->success($notification);
    }


    /**
     * Display current payment authorization notifications by notifiable id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function findCurrentPaymentAuthorizationByUserId()
    {
        $notification = Notification::query()
            ->where([
                [ 'type', '=', 'App\Notifications\PaymentAuthorizationNotification' ],
                [ 'notifiable_id', '=', request()->user('api')->id ],
                [ 'read_at', '=', NULL ]
            ])
            ->get()
            ->last();

        return !$notification ? $this->noContent() : $this->success($notification);
    }
}
