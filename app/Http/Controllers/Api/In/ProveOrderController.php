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

            $orderData = post_automira('/getInOrder', [
                'ID' => $data['id'],
            ])[0];


            $orderProducts = collect($orderData['products'])
                ->filter(fn(array $product) => $product['count'] > 0)
                ->map(fn(array $product) => [
                    'article' => $product['productId'],
                    'count' => $product['count'],
                    'shelf' => $product['shelfId'],
                ])
                ->values()
                ->toArray();


            $editData = [
                'id' => $data['id'],
                'agent' => $orderData['agentID'],
                'storage' => $orderData['storageId'],
                'responsible' => $orderData['responsibleId'],
                'productArray' => $orderProducts,
                'date' => now()->toDateTimeLocalString(),
            ];


            post_automira('/editInOrder', $editData)[0];

            post_automira('/provInOrder', $data)[0];
            
        } catch (AutomiraException $e) {
            return response()->noContent(400);
        }

        return response()->noContent(200);

    }
}
