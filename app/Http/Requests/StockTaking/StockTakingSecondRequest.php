<?php

namespace App\Http\Requests\StockTaking;

use Illuminate\Foundation\Http\FormRequest;

class StockTakingSecondRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

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
            'data.id' => ['required','string'],
            'data.products' => ['required','array'],
            'data.products.*.productId' => ['required','string'],
            'data.products.*.countAll' => ['required','numeric'],
        ];
    }
}
