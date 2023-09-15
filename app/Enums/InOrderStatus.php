<?php

namespace App\Enums;

use Exception;

enum InOrderStatus: string
{

    const IMPORT_LABEL = 'Звірка';
    const WAREHOUSE_LABEL = 'Склад';

    case IMPORT = 'Імпорт';
    case WAREHOUSE = 'Прихід';


    public static function casesValues(): array
    {
        return collect(self::cases())->map(fn($s) => $s->value)->toArray();
    }


    public static function swapLabel(string $forSwap): string
    {
        return match ($forSwap) {
            self::IMPORT->value => self::IMPORT_LABEL,
            self::WAREHOUSE->value => self::WAREHOUSE_LABEL,
            default => throw new Exception("unmatched value")
        };
    }

    public static function reverseSwapLabel(string $forSwap): string
    {
        return match ($forSwap) {
            self::WAREHOUSE_LABEL => self::WAREHOUSE->value,
            self::IMPORT_LABEL => self::IMPORT->value,
            default => throw new Exception("unmatched value")
        };
    }


}
