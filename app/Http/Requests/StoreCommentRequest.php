<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'pulse_id' => 'required|integer',
            'account_id' => 'required|integer',
            'content' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'comment_id' => 'nullable|integer',
        ];
    }
}
