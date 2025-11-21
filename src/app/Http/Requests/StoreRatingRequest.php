<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'score' => ['required','integer','between:1,5'],
            'comment' => ['nullable','string','max:255'],
        ];
    }
}
