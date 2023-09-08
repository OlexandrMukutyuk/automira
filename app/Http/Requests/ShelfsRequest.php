<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShelfsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid']
        ];
    }
}
