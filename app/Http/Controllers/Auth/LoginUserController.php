<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserLoginRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class LoginUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserLoginRequest $request): JsonResponse
    {
        $token = $request->authenticate();

        return response()->json(["success" => true, "access" => $token]);
    }
}
