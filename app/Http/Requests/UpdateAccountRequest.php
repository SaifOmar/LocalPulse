<?php

namespace App\Http\Requests;

use App\Rules\UniqueHandle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateAccountRequest extends FormRequest
{
    private $displayData = [
        'first_name',
        'last_name',
        'email',
    ];

    private $accountData = [
        'bio',
        'gender',
        'avatar',
        'handle',
    ];
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
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:accounts,email'],
            'handle' => ['nullable', 'string', 'max:255', new UniqueHandle($this->route('account')?->id)],
            'bio' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:255'],
            'avatar' => [
                'nullable',
                File::image()
                    ->min(1024)
                    ->max(12 * 1024)
                    ->dimensions(Rule::dimensions()->minWidth(1000)->minHeight(1000))
            ],
        ];
    }
    public function requestType(): string
    {
        $data = $this->validated();
        $dataKeys = array_keys($data);

        $hasDisplay = !empty(array_intersect($dataKeys, $this->displayData));
        $hasAccount = !empty(array_intersect($dataKeys, $this->accountData));

        return match (true) {
            $hasDisplay && !$hasAccount => 'display',
            !$hasDisplay && $hasAccount => 'account',
            default => 'mixed',
        };
    }
}
