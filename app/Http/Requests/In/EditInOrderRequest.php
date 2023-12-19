<?php

namespace App\Http\Requests\In;

use Illuminate\Foundation\Http\FormRequest;

class EditInOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid'],
            'agent' => ['required', 'uuid'],
            'storage' => ['required', 'uuid'],
            'responsible' => ['uuid'],
            'productArray' => ['required', 'array'],

            'productArray.*.article' => ['required', 'uuid'],
            'productArray.*.count' => ['required', 'numeric'],
            'productArray.*.countImport' => ['required', 'numeric'],
            'productArray.*.shelf' => ['nullable', 'string', 'present'],
        ];
    }
}
