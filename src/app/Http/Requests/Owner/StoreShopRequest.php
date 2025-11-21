<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && auth()->user()->role === 'owner'; }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:100'],
            'description' => ['nullable','string','max:1000'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ];
    }

    public function validated($key = null, $default = null)
    {
    $data = parent::validated($key, $default);
    $data['owner_id'] = auth()->id();
    return $data;
    }

}
