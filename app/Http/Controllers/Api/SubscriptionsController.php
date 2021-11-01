<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Subscription;
use App\Http\Controllers\Controller;
use App\Traits\SubscriptionServices;
use App\Http\Requests\Subscription\DestroyRequest;
use App\Http\Requests\Subscription\StoreRequest;

class SubscriptionsController extends Controller
{
    use SubscriptionServices;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Subscription::all();

        return !$result->count()
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Subscription\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $this->subscribe($request->user_email, $request->type);

        return $this->success(null, 'Subscribed successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Subscription $subscription)
    {
        return $this->success($subscription);
    }


    /**
     * Cancel a specific subscription in storage.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel()
    {
        auth('api')
            ->user()
            ->currentSubscription()
            ->update([
                'is_cancelled' => true,
                'cancelled_at' => Carbon::now(),
                'status' => 'cancelled'
            ]);

        return $this->success(null, 'Subscription cancelled successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\Subscription\DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        Subscription::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Subscription/s deleted successfully.');
    }
}
