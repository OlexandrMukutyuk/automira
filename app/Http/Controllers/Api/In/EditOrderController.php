<?php

namespace App\Http\Controllers\Api\In;

use App\Http\Requests\In\EditInOrderRequest;
use App\Http\Resources\In\InOrderResource;

class EditOrderController
{
    public function __invoke(EditInOrderRequest $request)
    {

        $data = $request->validated();

        $data = [
            ...$data,
            'responsible' => $data['responsible'] ?? '00000000-0000-0000-0000-000000000000',
            'date' => now()->toDateTimeLocalString(),
        ];

        $response = post_automira('/editInOrder', $data)[0];

        return InOrderResource::make($response);
    }
}
