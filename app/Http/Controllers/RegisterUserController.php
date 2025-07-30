<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegiserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    public  function __invoke(UserRegiserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('access')->plainTextToken;
        $user->access = $token;
        return response()->json(new UserResource($user))->setStatusCode(201);
    }
}
