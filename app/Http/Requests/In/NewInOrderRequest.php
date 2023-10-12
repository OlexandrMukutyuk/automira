<?php

namespace App\Http\Requests\In;

use Illuminate\Foundation\Http\FormRequest;

class NewInOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'storage' => ['required', 'uuid'],
            'responsible' => ['uuid'],
            'agent' => ['required', 'uuid']
        ];
    }
}
