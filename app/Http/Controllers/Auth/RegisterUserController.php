<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegiserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Actions\Accounts\CreateUserAccountAction;

class RegisterUserController extends Controller
{
    public function __invoke(UserRegiserRequest $request, CreateUserAccountAction $action): JsonResponse
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // create account for the user
        // REVISE ".plan.md" to know why it's this way
        $account = $action->first($user, $request->payload());

        $token = $user->createToken('access' . $account->handle)->plainTextToken;

        $user->access = $token;

        return response()->json(new UserResource($user))->setStatusCode(201);
    }
}
