<?php

namespace App\Http\Requests;

use App\Enums\IdentifierEnum;
use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $account = $this->getLoginAccount($this->identifier);
        if (!$account || !$this->authAttempt($account, $this->password)) {
            RateLimiter::hit($this->throttleKey(), 60);
            throw ValidationException::withMessages([
                'error' => ['The provided credentials are incorrect.'],
            ]);
        }
        $email = $account->user->email;
        RateLimiter::clear($this->throttleKey());
        // $account = Account::where("user_id", User::where("email", $email)->first()->id)->first();
        return User::where("email", $email)->first()->createToken('access' . $account->handle)->plainTextToken;
    }
    public function authAttempt(Account $account, string $password): bool
    {
        if ($account) {
            if (Hash::check($password, $account->password)) {
                return true;
            }
        }
        return false;
    }
    // could be cleaner
    public function getLoginAccount(string $identifier): ?Account
    {
        $identifiers = IdentifierEnum::cases();

        foreach ($identifiers as $field) {
            switch ($field) {
                case IdentifierEnum::EMAIL:
                    $user = User::where($field->value, $identifier)->with('accounts')->first();
                    if ($user?->firstAccount) {
                        return $user->first_account;
                    } else {
                        if ($acocunt = $user?->accounts->first()) {
                            return $acocunt;
                        }
                    }
                    break;

                case IdentifierEnum::HANDLE:
                    $account = Account::where('handle', strtolower($identifier))->first();
                    if ($account) {
                        return $account;
                    }
                    break;
            }
        }

        return null;
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
