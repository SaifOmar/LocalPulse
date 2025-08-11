<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegiserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Actions\Accounts\CreateUserAccountAction;
use App\Helpers\Helpers;
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Mail;

class RegisterUserController extends Controller
{
    public function __invoke(UserRegiserRequest $request, CreateUserAccountAction $action): JsonResponse
    {
        if (!env('APP_DEBUG')) {
            $location_data = Http::withHeaders([
                'User-Agent' => env("APP_NAME") .' (aliensaif@gmail.com)' // replace with your info
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                    "accept-language" => "en",
                    'lat' => $request->latitude,
                    'lon' => $request->longitude,
                    'format' => 'json'
            ]);
            $country = Arr::get($location_data, 'address.country');
            $city = Arr::get($location_data, 'address.city');
        }
        try {
            $user = User::create([
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "longitude" => $request->longitude,
                "latitude" => $request->latitude,
                "country" => $city ?? null,
                "city" => $country ?? null,
                "accuracy_meters" => $request->accuracy_meters,
            ]);

            $account = $action->first($user, $request->payload());

            $token = Helpers::createUserToken($user, $account->handle);

            Mail::to($user)->send(new UserRegisteredMail($account, $user));

            return response() ->json(new UserResource($user, $account, $token)) ->setStatusCode(201);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                ["error" => $e->getMessage()],
            ]);
        }
    }
}
