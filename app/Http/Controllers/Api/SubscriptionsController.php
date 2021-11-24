<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscription;
use App\Http\Controllers\Controller;
use App\Traits\SubscriptionServices;
use App\Http\Requests\Subscription\StoreRequest;
use App\Http\Requests\Subscription\UpdateRequest;
use App\Http\Requests\Subscription\DestroyRequest;

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
        $result = Subscription::with('user')->get();

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
        $result = $this->subscribe(
            $request->user_email, 
            $request->type, 
            $request->payment_method);

        return gettype($result) !== 'array' 
            ? $this->error($result)
            : $this->success($result, 'Subscribed successfully.');
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
     * Display the specified resource.
     *
     * @param  App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByAuthenticatedUser()
    {
        $subscriptions = auth('api')
            ->user()
            ->subscriptions()
            ->orderBy('subscribed_at', 'desc')
            ->get();

        return $this->success($subscriptions);
    }

    /**
     * Update a specified resource in storage.
     *
     * @param  App\Http\Requests\Subscription\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $result = $this->updateSubscription(
            $request->type, 
            $request->user_email, 
            $request->payment_method
        );

        $subscription = User::query()
            ->firstWhere('email', '=', $request->user_email)
            ->currentSubscription();

        return $result !== true
            ? $this->error($result)
            : $this->success($subscription, 'Plan updated successfully');
    }


    /**
     * Cancel a specific subscription in storage.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel()
    {
        $data = $this->cancelSubscription();

        return $this->success($data, 'Subscription cancelled successfully.');
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
