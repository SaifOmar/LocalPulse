<?php

namespace App\Http\Requests;

use App\Rules\UniqueHandle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class UserRegiserRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'handle' => ['required', 'string', 'max:255', new UniqueHandle($this->route('account')?->id)],
            'longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            'latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'accuracy_meters' => ['required', 'numeric', 'min:0', 'max:100'],
            'gender' => ['required', 'string', 'in:male,female,not specified'],
            'bio' => ['nullable', 'string', 'max:255'],
            'avatar' => [
                'nullable',
                File::image()
                    ->min(1024)
                    ->max(12 * 1024)
                    ->dimensions(Rule::dimensions()->minWidth(1000)->minHeight(1000))
            ]
        ];
    }
    /**
     * This method is used to create the payload for the account by removing the
     * fields that are not needed for the account. and makding the handle.
     */
    public function payload(): array
    {
        return Arr::except($this->validated(), ['first_name', 'last_name', 'email', 'longitude', 'latitude', 'accuracy_meters']);
    }
}
