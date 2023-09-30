<?php

namespace App\Http\Requests\In;

use Illuminate\Foundation\Http\FormRequest;

class ListInOrdersRequest extends FormRequest
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
