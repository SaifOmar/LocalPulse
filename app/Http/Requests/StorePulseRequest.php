<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePulseRequest extends FormRequest
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

        // $table->foreignIdFor(App\Models\Account::class)->constrained()->cascadeOnDelete();
        // $table->text('caption')->nullable();
        // $table->foreignIdFor(App\Models\Mood::class)->constrained()->nullOnDelete()->cascadeOnUpdate();
        // $table->enum('type', ['image','video'])->default("image");
        // $table->string('url')->nullable();
        // $table->timestamps();
        return [
            "caption" => "nullable|string|max:255",
            "media" => "required|file",
            "mood_id" => "required|exists:moods,id",
            "type" => "required|in:image,video",
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
        ];
    }
}
