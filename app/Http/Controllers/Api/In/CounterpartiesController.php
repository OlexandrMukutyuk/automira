<?php

namespace App\Http\Controllers\Api\In;

use Http;

class CounterpartiesController
{
    public function __invoke()
    {
        return Http::automira()->get('/getInCounterparties')->json();
    }
}
