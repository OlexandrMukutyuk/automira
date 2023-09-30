<?php

namespace App\Http\Controllers\Api\In;

use App\Http\Requests\In\NewInOrderRequest;
use App\Http\Resources\In\InOrderResource;

class NewOrderController
{
    public function __invoke(NewInOrderRequest $request)
    {
        $data = $request->validated();

        $data = [
            ...$data,
            'agent' => '00000000-0000-0000-0000-000000000000',
            'date' => now()->toDateTimeLocalString(),
            'productArray' => []
        ];

        $response = post_automira('/newInOrder', $data)[0];

        return InOrderResource::make($response);

    }
}
