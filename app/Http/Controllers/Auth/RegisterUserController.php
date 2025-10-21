<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegiserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Actions\Accounts\CreateUserAccountAction;
use App\Helpers\Helpers;
use App\Mail\UserRegisteredMail;
use App\Services\Locations\Users\UserLocationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Mail;
use App\Http\Resources\UserResource;

class RegisterUserController extends Controller
{
    public function __invoke(UserRegiserRequest $request, CreateUserAccountAction $action): JsonResponse
    {
        Log::info($request);
        $service = new UserLocationService();
        $service->getLocation($request->longitude, $request->latitude);
        Log::info($service);
        try {
            $user = User::create([
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "longitude" => $request->longitude,
                "latitude" => $request->latitude,
                "accuracy_meters" => $request->accuracy_meters,
                "country" => $city ?? null,
                "city" => $country ?? null,
            ]);

            $account = $action->first($user, $request->payload());

            $token = Helpers::createUserToken($user, $account->handle);

            Mail::to($user)->send(new UserRegisteredMail($account, $user));

            return response()->json(new UserResource($user, $account, $token))->setStatusCode(201);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw ValidationException::withMessages([
                ["error" => $e->getMessage()],
            ]);
        }
    }
}
