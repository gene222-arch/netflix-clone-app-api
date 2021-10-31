<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Traits\Api\ApiServices;
use App\Traits\Auth\AuthServices;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Stevebauman\Location\Facades\Location;
use App\Http\Requests\Auth\RegisterRequest;
use App\Traits\SubscriptionServices;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use ApiServices, AuthServices, SubscriptionServices;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api');
    }


    /**
     * Create's a user 
     *
     * @param  RegisterRequest  $request
     * @param  App\Models\User  $user
     * @return Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request, User $user)
    {
        try {
            DB::transaction(function () use ($request, $user)
            {
                $userDetails = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'avatar_path' => $request->avatar_path
                ];

                $user = $user->query()->create($userDetails);
                $user->sendEmailVerificationNotification();
                $user->sendPaymentAuthorizationNotification($request->check_out_url);
                $user->assignRole($request->role);

                if ($request->has('plan_type')) {
                    $this->preSubscription($request->plan_type, $user->id);
                }

                /** Save user location if access is allowed */
                if ( $request->allow_access_to_location && $address = Location::get($request->ip()) ) 
                {
                    $userAddressDetails = [
                        'user_id' => $user->id,
                        'country' => $address->countryName,
                        'country_code' => $address->countryCode,
                        'region_code' => $address->regionCode,
                        'region_name' => $address->regionName,
                        'city_name' => $address->cityName,
                        'zip_code' => $address->zipCode,
                        'area_code' => $address->areaCode
                    ];

                    $user->address()->create($userAddressDetails);
                }

                if (! Auth::attempt($request->safe(['email', 'password']))) {
                    return $this->error('Login Failed!');
                }
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->token(
            $this->getPersonalAccessToken($request),
            'Registered successfully, an email verification has been sent to your account.'
        );
    }
}
