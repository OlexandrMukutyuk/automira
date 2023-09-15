<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InOrdersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'storages' => ['array'],
            'counterparties' => ['array'],
            'statuses' => ['array'],
        ];
    }
}
