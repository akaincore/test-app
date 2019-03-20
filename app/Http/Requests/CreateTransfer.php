<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateTransfer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'recipient_id' => 'required|exists:users,id|not_in:' . Auth::id(),
            'time' => 'required|date_format:Y-m-d H|after:' . Carbon::now()->addMinute()->format('Y-m-d H'),
            'sum' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'recipient_id.required' => 'Enter user id',
            'recipient_id.exists' => 'Entered user id does not exists',
            'recipient_id.not_in' => 'You can\'t create transfer to yourself',
            'sum.required' => 'Enter sum',
            'sum.numeric' => 'Enter numeric sum',
            'time.required' => 'Choose transfer date and time',
            'time.date_format' => 'Invalid date or time format. Valid value example: 2019-01-01',
        ];
    }
}
