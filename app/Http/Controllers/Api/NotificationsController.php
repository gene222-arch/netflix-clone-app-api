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
        $notification = Notification::query()
            ->where([
                [ 'type', '=', 'App\Notifications\PaymentAuthorizationNotification' ],
                [ 'notifiable_id', '=', request()->user('api')->id ],
                [ 'read_at', '=', NULL ]
            ])
            ->get()
            ->last();

        return $this->success($notification);
    }
}
