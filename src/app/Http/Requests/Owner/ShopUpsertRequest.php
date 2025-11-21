<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class ShopUpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('access-owner') ?? false;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required','string','max:255'],
            'area'        => ['nullable','string','max:50'],
            'genre'       => ['nullable','string','max:50'],
            'description' => ['nullable','string'],
            'image'       => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ];
    }
}

