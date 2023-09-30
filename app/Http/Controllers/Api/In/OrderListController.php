<?php

namespace App\Http\Controllers\Api\In;

use App\Enums\InOrderStatus;
use App\Enums\InOrderType;
use App\Http\Requests\In\ListInOrdersRequest;
use Cache;

class OrderListController
{
    public function __invoke(ListInOrdersRequest $request)
    {
        $data = $request->validated();

        Cache::forget('in_orders');
        $response = Cache::remember('in_orders', now()->addDay(), function () {
            if (config('services.automira.fake_orders')) {
                return $this->fakeOrders();
            }

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

                $status = InOrderStatus::calculateStatus($proved, $startedScan, $type);


                return [
                    'kontragent' => $el['agent'],
                    'storage' => $el['storage'],
                    'date' => $el['date'],
                    'number' => $el['number'],
                    'type' => $type,
                    'status' => $status->value,
                    'id' => $el['id']
                ];
            })
            ->values();
    }


    private function fakeOrders(): array
    {
        return json_decode('[
{
"id": "ef230051-5e24-11ee-9da2-44a8422e11c9",
"number": "MG-00001925",
"date": "28.09.2023 19:02:00",
"agent": "",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Імпорт",
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
"startedScan": true
},
{
"id": "7556134b-5e1e-11ee-9da2-44a8422e11c9",
"number": "MG-00001920",
"date": "28.09.2023 19:00:00",
"agent": "",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Імпорт",
"proved": false,
"startedScan": false
},
{
"id": "909d5992-5e1e-11ee-9da2-44a8422e11c9",
"number": "MG-00001921",
"date": "28.09.2023 19:00:00",
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
"id": "98157732-5e1e-11ee-9da2-44a8422e11c9",
"number": "MG-00001922",
"date": "28.09.2023 19:00:00",
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
"id": "1481485c-5e1f-11ee-9da2-44a8422e11c9",
"number": "MG-00001923",
"date": "28.09.2023 19:00:00",
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
"id": "37abf6ed-5e1f-11ee-9da2-44a8422e11c9",
"number": "MG-00001924",
"date": "28.09.2023 19:00:00",
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
"id": "4a24a26f-5e01-11ee-9da2-44a8422e11c9",
"number": "MG-00001915",
"date": "28.09.2023 18:00:00",
"agent": "",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": false,
"startedScan": false
},
{
"id": "fd51f3c8-5e01-11ee-9da2-44a8422e11c9",
"number": "MG-00001916",
"date": "28.09.2023 18:00:00",
"agent": "",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": false,
"startedScan": false
},
{
"id": "5c92886b-5e1e-11ee-9da2-44a8422e11c9",
"number": "MG-00001919",
"date": "28.09.2023 18:00:00",
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
"id": "0a1099c6-5e00-11ee-9da2-44a8422e11c9",
"number": "MG-00001912",
"date": "28.09.2023 16:07:57",
"agent": "Пімонов Олександр",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "d6010319-5dff-11ee-9da2-44a8422e11c9",
"number": "MG-00001911",
"date": "28.09.2023 16:06:33",
"agent": "Романчук Наталія",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "eba6863c-5dfb-11ee-9da2-44a8422e11c9",
"number": "MG-00001910",
"date": "28.09.2023 15:38:28",
"agent": "NEWAVTO",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "bbd8821c-5dfb-11ee-9da2-44a8422e11c9",
"number": "MG-00001909",
"date": "28.09.2023 15:37:09",
"agent": "BROCAR Гасіч Андрій ФОП",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "5b0f9fc4-5df5-11ee-9da2-44a8422e11c9",
"number": "MG-00001908",
"date": "28.09.2023 14:52:48",
"agent": "АВТОДІМСЕРВІС ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "a8e8919d-5df4-11ee-9da2-44a8422e11c9",
"number": "MG-00001907",
"date": "28.09.2023 14:46:49",
"agent": "АВТОДІМСЕРВІС ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "1d3bb6b2-5def-11ee-9da2-44a8422e11c9",
"number": "MG-00001906",
"date": "28.09.2023 14:07:26",
"agent": "ДАЛІС АУТОМОТІВ ГРУП ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "a1f3d000-5dd7-11ee-9da2-44a8422e11c9",
"number": "MG-00001905",
"date": "28.09.2023 11:22:23",
"agent": "ДАЛІС АУТОМОТІВ ГРУП ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "dc178223-5dd6-11ee-9da2-44a8422e11c9",
"number": "MG-00001904",
"date": "28.09.2023 11:13:12",
"agent": "Лікас Юрій",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "cc84b3bd-5dd5-11ee-9da2-44a8422e11c9",
"number": "MG-00001903",
"date": "28.09.2023 11:07:41",
"agent": "ЕЛІТ-УКРАЇНА ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "9ee6282a-5dd3-11ee-9da2-44a8422e11c9",
"number": "MG-00001902",
"date": "28.09.2023 10:49:59",
"agent": "MRS Parts Миколайчук Роман Луцьк",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "e1109b5a-5dd1-11ee-9da2-44a8422e11c9",
"number": "MG-00001901",
"date": "28.09.2023 10:37:33",
"agent": "Плюш Олександр",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "76961455-5dcf-11ee-9da2-44a8422e11c9",
"number": "MG-00001900",
"date": "28.09.2023 10:20:16",
"agent": "ELEMENT Клепик Вадим Сергійович",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "bc317132-5dcc-11ee-9da2-44a8422e11c9",
"number": "MG-00001899",
"date": "28.09.2023 10:04:09",
"agent": "Думич Вадим Васильович ФОП",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "8bc32587-5e00-11ee-9da2-44a8422e11c9",
"number": "MG-00001913",
"date": "28.09.2023 10:00:00",
"agent": "",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": false,
"startedScan": false
},
{
"id": "d391334b-5e00-11ee-9da2-44a8422e11c9",
"number": "MG-00001914",
"date": "28.09.2023 10:00:00",
"agent": "",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": false,
"startedScan": false
},
{
"id": "6c23d38e-5dc5-11ee-9da2-44a8422e11c9",
"number": "MG-00001898",
"date": "28.09.2023 09:08:25",
"agent": "MRS Parts Миколайчук Роман Луцьк",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "9374795c-5d3d-11ee-9da2-44a8422e11c9",
"number": "MG-00001897",
"date": "27.09.2023 16:55:55",
"agent": "AutoDetail Пушкін Іван ФОП",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "b84579d2-5d3c-11ee-9da2-44a8422e11c9",
"number": "MG-00001896",
"date": "27.09.2023 16:49:48",
"agent": "Сигачов Олександр  Костянтинович",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "4f08a663-5d3c-11ee-9da2-44a8422e11c9",
"number": "MG-00001895",
"date": "27.09.2023 16:46:51",
"agent": "Усатенко Юрій",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "f75ae1b0-5d3b-11ee-9da2-44a8422e11c9",
"number": "MG-00001894",
"date": "27.09.2023 16:44:24",
"agent": "Губаренко  Денис  Иванович",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "494ca3d4-5d36-11ee-9da2-44a8422e11c9",
"number": "MG-00001893",
"date": "27.09.2023 16:03:45",
"agent": "Кошнецький Валерій Ігорович",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "83c7ceb4-5d34-11ee-9da2-44a8422e11c9",
"number": "MG-00001892",
"date": "27.09.2023 15:57:43",
"agent": "ДАЛІС АУТОМОТІВ ГРУП ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "dcee5960-5d29-11ee-9da2-44a8422e11c9",
"number": "MG-00001891",
"date": "27.09.2023 14:35:48",
"agent": "СИД ВЕСТ ГРУП ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "63e48a60-5d28-11ee-9da2-44a8422e11c9",
"number": "MG-00001890",
"date": "27.09.2023 14:24:16",
"agent": "ФОП Башняк Тарас Володимирович",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "36f7d05b-5d17-11ee-9da2-44a8422e11c9",
"number": "MG-00001889",
"date": "27.09.2023 12:21:20",
"agent": "NEWAVTO",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "ad5069ae-5d05-11ee-9da2-44a8422e11c9",
"number": "MG-00001888",
"date": "27.09.2023 10:18:34",
"agent": "ЕЛІТ-УКРАЇНА ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "f87900bf-5c55-11ee-9da2-44a8422e11c9",
"number": "MG-00001885",
"date": "26.09.2023 13:22:46",
"agent": "ДАЛІС АУТОМОТІВ ГРУП ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "a67841f4-5c55-11ee-9da2-44a8422e11c9",
"number": "MG-00001884",
"date": "26.09.2023 13:17:22",
"agent": "ЕЛІТ-УКРАЇНА ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "9d0d89b7-5ba4-11ee-9da2-44a8422e11c9",
"number": "MG-00001878",
"date": "25.09.2023 16:08:27",
"agent": "bipauto (Абашия Міріан Рамінович)",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "83461df4-5ba4-11ee-9da2-44a8422e11c9",
"number": "MG-00001877",
"date": "25.09.2023 16:07:44",
"agent": "Дмитров Олександр",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "ada5b513-5ba3-11ee-9da2-44a8422e11c9",
"number": "MG-00001876",
"date": "25.09.2023 16:01:46",
"agent": "Квітницкий Сергій",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "6784cbef-5ba3-11ee-9da2-44a8422e11c9",
"number": "MG-00001875",
"date": "25.09.2023 15:59:49",
"agent": "Яворський Олександр",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "5fe92618-5b88-11ee-9da2-44a8422e11c9",
"number": "MG-00001874",
"date": "25.09.2023 12:46:21",
"agent": "EXIST.UA АБС УКРАЇНА ТзОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "af3029d0-5b7a-11ee-9da2-44a8422e11c9",
"number": "MG-00001873",
"date": "25.09.2023 11:08:24",
"agent": "Куркчі Юлія Сергійовна",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
},
{
"id": "95ebda13-5b79-11ee-9da2-44a8422e11c9",
"number": "MG-00001872",
"date": "25.09.2023 11:02:38",
"agent": "ДАЛІС АУТОМОТІВ ГРУП ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "93db627a-5b6d-11ee-9da2-44a8422e11c9",
"number": "MG-00001871",
"date": "25.09.2023 09:35:09",
"agent": "ФОРМА ПАРТС ТОВ",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Соколовський Руслан Павлович",
"operation": "Від постачальника",
"status": "Прихід",
"proved": true,
"startedScan": true
},
{
"id": "31d01e8b-59f1-11ee-9da2-44a8422e11c9",
"number": "MG-00001870",
"date": "23.09.2023 12:11:37",
"agent": "Соколюк Василь Володимирович",
"storage": "Склад №3 (оперативний)",
"branchOffice": "Луцька філія",
"responsible": "Лихач Левій Іванович",
"operation": "Повернення",
"status": "Повернення",
"proved": true,
"startedScan": true
}]', true);
    }


}
