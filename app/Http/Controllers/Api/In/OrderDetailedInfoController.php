<?php

namespace App\Http\Controllers\Api\In;

use App\Exceptions\AutomiraException;
use App\Http\Resources\In\InOrderResource;

class OrderDetailedInfoController
{
    /**
     * @throws AutomiraException
     */
    public function __invoke(string $uuid)
    {
        $data = post_automira('/getInOrder', [
            'ID' => $uuid,
        ])[0];

        $data['id'] = $uuid;

        return InOrderResource::make($data);
    }
}
