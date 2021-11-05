<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
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
        $notifications = auth('api')
            ->user()
            ->notifications
            ->filter(fn ($notification) => $notification->type === 'App\Notifications\PaymentAuthorizationNotification')
            ->map(function ($notification) {
                $notification->time_ago = $notification->created_at->diffForHumans();

                return $notification;
            });

        return !$notifications->count() ? $this->noContent() : $this->success($notifications);
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

    public function markAllPaymentAuthNotifsAsRead()
    {
        auth('api')
            ->user()
            ->unreadNotifications
            ->map(function ($notification) {
                if ($notification->type === 'App\Notifications\PaymentAuthorizationNotification') {
                    $notification->markAsRead();
                }
            });

        return $this->success(NULL, 'Notifications mark all as read');
    }

    public function clearPaymentAuthNotifs()
    {
        auth('api')
            ->user()
            ->notifications()
            ->where('type', 'App\Notifications\PaymentAuthorizationNotification')
            ->delete();


        return $this->success(NULL, 'Notifications cleared');
    }
}
