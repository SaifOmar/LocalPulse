<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegiserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
// use Illuminate\Support\Facades\Hash;
use App\Actions\Accounts\CreateUserAccountAction;
use App\Helpers\Helpers;
use App\Mail\UserRegisteredMail;
// use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Mail;

use function event;
use function mail;

// use Ramsey\Collection\Collection;

class RegisterUserController extends Controller
{
    public function __invoke(UserRegiserRequest $request, CreateUserAccountAction $action): JsonResponse
    {
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
            ]);

            $account = $action->first($user, $request->payload());

            $token = Helpers::createUserToken($user, $account->handle);
            Mail::to($user)->send(new UserRegisteredMail($account, $user));

            return response()->json(new UserResource($user, $account, $token))->setStatusCode(201);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([['error' => $e->getMessage()]]);
        }
    }
}
