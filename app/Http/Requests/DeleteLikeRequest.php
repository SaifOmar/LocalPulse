<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteLikeRequest extends FormRequest
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
            'liked_id' => 'required|integer|',
            'liked_type' => 'required|string|in:pulse,comment,story',
        ];
    }
}
