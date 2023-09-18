<?php

namespace App\Http\Controllers\Api;

use App\Enums\InOrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\InOrdersRequest;
use Cache;
use Http;

class InController extends Controller
{

    public function counterparties()
    {
        return Http::automira()->get('/getInCounterparties')->json();
    }


    public function filters()
    {
        return Cache::remember('filters_in_orders', 60, function () {
            $neededFilters = [
                'storages',
                'statuses',
            ];

            $agents = collect(Http::automira()->get('/getInCounterparties')->json())
                ->map(function ($el) {
                    return $el['counterparty'];
                });

            $filtersCollection = collect(Http::automira()->get('/filtersInOrder')->json()[0]);

            return $filtersCollection
                ->filter(function ($el, $key) use ($neededFilters) {
                    return in_array($key, $neededFilters);
                })
                ->map(function ($el, $key) {
                    if ($key === 'storages') {
                        return collect($el)->flatten();
                    }

                    if ($key === 'statuses') {
                        return collect($el)
                            ->filter(fn($el) => in_array($el, InOrderStatus::casesValues()))
                            ->map(fn($el) => InOrderStatus::swapLabel($el))->values();
                    }
                })
                ->put('counterparties', $agents);
        });
    }

    public function orders(InOrdersRequest $request)
    {
        $data = $request->validated();

        $response = Cache::remember('in_orders', now()->addDay(), function () {
            return Http::automira()->get('/getInOrders')->json();
        });


        return collect($response)
            ->orderFilter('storage', $data['storages'] ?? [])
            ->orderFilter('agent', $data['counterparties'] ?? [])
            ->orderFilter('status', InOrderStatus::casesValues())
            ->when(count($data['statuses'] ?? []))
            ->orderFilter(
                'status',
                collect($data['statuses'])
                    ->map(fn($s) => InOrderStatus::reverseSwapLabel($s))->toArray()
            )
            ->map(function ($el) {
                return [
                    'kontragent' => $el['agent'],
                    'storage' => $el['storage'],
                    'date' => $el['date'],
                    'number' => $el['number'],
                    'status' => InOrderStatus::swapLabel($el['status']),
                    'id' => $el['id']
                ];
            })
            ->values();
    }

    public function updateOrders(): void
    {
        Cache::forget('in_orders');
    }
}
