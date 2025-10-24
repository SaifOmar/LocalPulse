<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Users\CreateUserAction;
use App\Actions\Accounts\CreateUserAccountAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegiserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Helpers\Helpers;
use App\Mail\UserRegisteredMail;
use App\Services\Locations\Users\UserLocationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Mail;
use App\Http\Resources\UserResource;

class RegisterUserController extends Controller
{
    public function __invoke(UserRegiserRequest $request, CreateUserAction $action, CreateUserAccountAction $accountAction): JsonResponse
    {
        $service = new UserLocationService();
        $service->getLocation($request->longitude, $request->latitude);

        try {

            $result = $action->extractFromRequest($request);
            $user = $result->create();
            $account = $accountAction->first($user, $request->payload());
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
