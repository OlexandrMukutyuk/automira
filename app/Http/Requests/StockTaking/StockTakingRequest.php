<?php

namespace App\Http\Requests\StockTaking;

use Illuminate\Foundation\Http\FormRequest;

class StockTakingRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'action' => ['required'],
            'data' => ['required','array'],
            'data.shelfId' => ['required','string'],
            'data.responsibleId' => ['required','string'],
        ];
    }
}
