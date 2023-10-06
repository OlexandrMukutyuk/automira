<?php

namespace App\Http\Resources\In;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InOrderProductResource extends JsonResource
{

    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            "number" => $this['number'],
            "name" => $this['name'],
            "vendor" => $this['vendor'],
            "productId" => $this['productId'],
            "article" => $this['article'],

            "shelf" => $this['shelf'],
            "shelfCode" => $this['shelfCode'],

            "barcode" => $this['barcode'],
            "count" => $this['count'],
            "countImport" => $this['countImport'],
            "orderId" => $this['orderId']

        ];
    }
}
