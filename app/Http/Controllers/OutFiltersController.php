<?php

namespace App\Http\Controllers;

use App\Enums\InOrderType;
use Cache;
use Http;


class OutFiltersController extends Controller
{
    public function __invoke()
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

            $filtersCollection = collect(Http::automira()->get('/filtersOutOrder')->json()[0]);

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
                            ->filter(fn($el) => in_array($el, InOrderType::casesValues()))
                            ->map(fn($el) => InOrderType::swapLabel($el))->values();
                    }
                })
                ->put('counterparties', $agents);
        });
    }
}
