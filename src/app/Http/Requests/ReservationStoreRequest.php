<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ReservationStoreRequest extends FormRequest
{
public function authorize(): bool { return true; }


public function rules(): array
{
return [
'reserved_at' => ['required','date','after:now'],
'num_of_guests' => ['required','integer','min:1','max:20'],
];
}
}