<?php

namespace App\Http\Controllers\Api\In;

use Illuminate\Http\Request;

class ProveOrderController
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'uuid']
        ]);


        post_automira('/provInOrder', $data)[0];

        return response()->noContent(200);

    }
}
