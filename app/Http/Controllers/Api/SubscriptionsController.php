<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\DestroyRequest;
use App\Http\Requests\Subscription\UpdateOrStoreRequest;
use App\Traits\SubscriptionServices;

class SubscriptionsController extends Controller
{
    use ApiResponser, SubscriptionServices;

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
     * @param  App\Http\Requests\Subscription\UpdateOrStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UpdateOrStoreRequest $request)
    {
        $this->subscribe($request->type);

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
     * Update the specified resource in storage.
     * ! Uknown process
     * @param  App\Http\Requests\Subscription\UpdateOrStoreRequest  $request
     * @param  App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateOrStoreRequest $request, Subscription $subscription)
    {
        $subscription->update([

        ]);
        
        return $this->success(null, 'Subscription updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\Subscription\DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        return $this->success(null, 'Subscription deleted successfully.');
    }
}
