<?php

namespace App\Enums;

use Exception;

enum InOrderStatus: string
{
    case IMPORT_STATUS_LABEL = 'Імпорт';
    case WAREHOUSE_STATUS_LABEL = 'Прихід';


    public static function casesValues(): array
    {
        return collect(self::cases())->map(fn($s) => $s->value)->toArray();
    }


    public static function swapLabel(string $forSwap): string
    {
        return match ($forSwap) {
            self::WAREHOUSE_STATUS_LABEL->value => 'Звірка',
            self::IMPORT_STATUS_LABEL->value => "Склад",
            default => throw new Exception("unmatched value")
        };
    }


}
