<?php

namespace App\Http\Requests;

use App\Enums\IdentifierEnum;
use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "identifier" => "required|string|max:255",
            "password" => "required|string|min:8",
        ];
    }

    public function authenticate(): string
    {
        $this->ensureIsNotRateLimited();
        $credentials = $this->resolveCredentials($this->identifier, $this->password);
        if (empty($credentials) || !Auth::attempt($credentials)) {
            RateLimiter::hit($this->throttleKey(), 60);
            throw ValidationException::withMessages([
                'error' => ['The provided credentials are incorrect.'],
            ]);
        }
        RateLimiter::clear($this->throttleKey());
        $account = Account::where("user_id", User::where("email", $credentials['email'])->first()->id)->first();
        return User::where("email", $credentials['email'])->first()->createToken('access' . $account->handle)->plainTextToken;
    }
    // could be cleaner
    public function resolveCredentials(string $identifier, string $password): array
    {
        $identifiers = IdentifierEnum::cases();
        foreach ($identifiers as $field) {
            switch ($field) {
                case IdentifierEnum::EMAIL:
                    if ($user = User::where($field->value, $identifier)->first()) {
                        return [
                            "email" => $user->email,
                            "password" => $password,
                        ];
                    }
                    break;
                case IdentifierEnum::HANDLE:
                    if ($user = Account::where('handle', $identifier)->first()->user) {
                        return [
                            "email" => $user->email,
                            "password" => $password,
                        ];
                    }
                    break;
            }
        }
        // fallback would fail later
        return ["email" => $identifier, "password" => $password];
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
    public function throttleKey(): string
    {
        return  Str::transliterate(Str::lower($this->string('identifier')) . '|' . $this->ip());
    }
}
