<?php

namespace App\Http\Resources\In;

use App\Enums\InOrderStatus;
use App\Enums\InOrderType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InOrderResource extends JsonResource
{

    public static $wrap = null;

    public function toArray(Request $request): array
    {

        return [
            'id' => $this->when(isset($this['id']), fn() => $this['id']),
            'number' => $this['number'],
            'date' => $this['date'],
            'agent' => $this['agent'],
            'agentID' => $this['agentID'],

            'storage' => $this['storage'],
            'storageId' => $this['storageId'],

            'branchOffice' => $this['branchOffice'],
            'responsible' => $this['responsible'],
            'responsibleId' => $this['responsibleId'],
            'type' => $type = InOrderType::swapLabel($this['status']),
            'status' => InOrderStatus::calculateStatus($this['proved'], $this['startedScan'], $type),
            'products' => InOrderProductResource::collection(array_map(fn($el) => [...$el, 'orderId' => $this['id']], $this['products'])),

            'deletionMark' => $this['deletionMark']
        ];
    }
}
