<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [

            'date'   => ['required','date','after_or_equal:today'],
            'time'   => ['required','date_format:H:i'],
            'people' => ['required','integer','min:1','max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'          => ':attributeは必須です。',
            'date'              => ':attributeは日付で指定してください。',
            'after_or_equal'    => ':attributeには今日以降の日付を指定してください。',
            'date_format'       => ':attributeの形式が正しくありません（例 18:00）。',
            'integer'           => ':attributeは整数で指定してください。',
            'min'               => ':attributeは:min以上で指定してください。',
            'max'               => ':attributeは:max以下で指定してください。',
        ];
    }

    public function attributes(): array
    {
        return [
            'date'   => '来店日',
            'time'   => '時間',
            'people' => '人数',
        ];
    }
}
