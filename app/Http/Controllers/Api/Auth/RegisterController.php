<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Traits\Api\ApiServices;
use App\Traits\Api\ApiResponser;
use App\Traits\Auth\AuthServices;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Stevebauman\Location\Facades\Location;
use App\Http\Requests\Auth\RegisterRequest;

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

    use ApiResponser, ApiServices, AuthServices;

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
     * Create's a user with the attempt to log in 
     *
     * @param RegisterRequest $request
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
                ];

                $user->query()->create($userDetails)->sendEmailVerificationNotification();

                /** Save user location if access is allowed */
                if ( $request->allow_access_to_location && $address = Location::get($request->ip()) ) 
                {
                    $userAddressDetails = [
                        'country' => $address['country'],
                        'country_code' => $address['countryCode'],
                        'region_code' => $address['regionCode'],
                        'region_name' => $address['regionName'],
                        'city_name' => $address['cityName'],
                        'zip_code' => $address['zipCode'],
                        'area_code' => $address['areaCode']
                    ];

                    $user->address()->create($userAddressDetails);
                }
        
                if (! Auth::attempt($request->only('email', 'password'))) {
                    return $this->error('Credentials mismatch', 401);
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
