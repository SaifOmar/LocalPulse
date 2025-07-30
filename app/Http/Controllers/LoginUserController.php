<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Enums\IdentifierEnum;
use Illuminate\Support\Facades\Auth;

class LoginUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserLoginRequest $request): JsonResponse
    {
        $credentials = $this->resolveCredentials($request->identifier, $request->password);
        if (empty($credentials) || !Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'error' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = User::where("email", $credentials['email'])->first()->createToken('access')->plainTextToken;
        return response()->json(["success" => true, "access" => $token]);
    }


    protected function resolveCredentials(string $identifier, string $password): array
    {
        $identifiers = IdentifierEnum::cases();
        foreach ($identifiers as $field) {
            if ($user = User::where($field->value, $identifier)->first()) {
                return [
                    "email" => $user->email,
                    'password' => $password,
                ];
            }
        }
        // fallback would fail later
        return ["email" => $identifier, "password" => $password];
    }
}
