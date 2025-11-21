<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('reservation'));
    }

    public function rules(): array
    {
        return [
            'reserved_at' => ['required','date','after:now'],
            'guests'      => ['required','integer','min:1','max:20'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $reservation = $this->route('reservation');

            $visit = Carbon::parse($reservation->reserved_at);
            $deadline = $visit->copy()->startOfDay()->subDay()->endOfDay();

            if (now()->gt($deadline)) {
                $v->errors()->add('reserved_at', '当日の予約は変更できません。（前日まで）');
            }
        });
    }

    public function messages(): array
    {
        return [
            'reserved_at.after' => '現在より後の日時を指定してください。',
        ];
    }
}
