<?php

namespace App\Http\Controllers\Api\In;

use App\Exceptions\AutomiraException;
use Illuminate\Http\Request;

class ProveOrderController
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'uuid']
        ]);


        try {
            post_automira('/provInOrder', $data)[0];
        } catch (AutomiraException $e) {
            return response()->noContent(400);
        }

        return response()->noContent(200);

    }
}
