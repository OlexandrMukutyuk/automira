<?php

namespace App\Http\Controllers\Api\In;

use Cache;

class UpdateOrdersController
{
    public function __invoke()
    {
        Cache::forget('in_orders');

        return response()->noContent(200);
    }
}
