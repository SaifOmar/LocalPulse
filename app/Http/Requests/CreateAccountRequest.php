<?php

namespace App\Http\Requests;

use App\Models\Account;
use App\Rules\UniqueHandle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class CreateAccountRequest extends FormRequest
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
            'hanlde' => ['required', 'string', 'max:255', 'unique:accounts'],
            'bio' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:255'],
            'handle' => ['required', 'string', 'max:255', new UniqueHandle($this->route('account')?->id)],
            'avatar' => [
                'nullable',
                File::image()
                    ->min(1024)
                    ->max(12 * 1024)
                    ->dimensions(Rule::dimensions()->minWidth(1000)->minHeight(1000))
            ],
        ];
    }
    // should here call the image compression helper
}
