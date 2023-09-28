<?php

namespace App\Http\Controllers\Api;

use App\Enums\InOrderStatus;
use App\Enums\InOrderType;
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
                            ->filter(fn($el) => in_array($el, InOrderType::casesValues()))
                            ->map(fn($el) => InOrderType::swapLabel($el))->values();
                    }
                })
                ->put('counterparties', $agents);
        });
    }

    public function orders(InOrdersRequest $request)
    {
        $data = $request->validated();

        Cache::forget('in_orders');
        $response = Cache::remember('in_orders', now()->addDay(), function () {
//            return $this->fakeOrders();

            return get_automira("/getInOrders");
        });


        return collect($response)
            ->orderFilter('storage', $data['storages'] ?? [])
            ->orderFilter('agent', $data['counterparties'] ?? [])
            ->orderFilter('status', InOrderType::casesValues())
            ->when(count($data['statuses'] ?? []))
            ->orderFilter(
                'status',
                collect($data['statuses'])
                    ->map(fn($s) => InOrderType::reverseSwapLabel($s))->toArray()
            )
            ->map(function ($el) {
                $proved = $el['proved'];
                $startedScan = $el['startedScan'];

                $type = InOrderType::swapLabel($el['status']);

                $status = $proved ? InOrderStatus::PROVED->value : InOrderStatus::UNPROVED->value;

                if ($type === InOrderType::IMPORT_LABEL && !$proved && !$startedScan) {
                    $status = InOrderStatus::DONT_SCANNED->value;
                }

                return [
                    'kontragent' => $el['agent'],
                    'storage' => $el['storage'],
                    'date' => $el['date'],
                    'number' => $el['number'],
                    'type' => $type,
                    'status' => $status,
                    'id' => $el['id']
                ];
            })
            ->values();
    }

    public function updateOrders(): void
    {
        Cache::forget('in_orders');
    }

    private function fakeOrders(): array
    {
        return json_decode('[{
                "id": "ef230051-5e24-11ee-9da2-44a8422e11c9",
                "number": "MG-00001925",
                "date": "28.09.2023 19:02:00",
                "agent": "",
                "storage": "Склад №3 (оперативний)",
                "branchOffice": "Луцька філія",
                "responsible": "Соколовський Руслан Павлович",
                "operation": "Від постачальника",
                "status": "Прихід",
                "proved": false,
                "startedScan": true
                },
                {
                "id": "03b80c2d-5e25-11ee-9da2-44a8422e11c9",
                "number": "MG-00001926",
                "date": "28.09.2023 19:02:00",
                "agent": "",
                "storage": "Склад №3 (оперативний)",
                "branchOffice": "Луцька філія",
                "responsible": "Соколовський Руслан Павлович",
                "operation": "Від постачальника",
                "status": "Імпорт",
                "proved": true,
                "startedScan": false
                }]', true);
    }
}
