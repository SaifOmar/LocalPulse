<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Enums\IdentifierEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
