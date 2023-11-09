<?php

namespace App\Enums;


enum InOrderStatus: int
{

    case PROVED = 1;
    case UNPROVED = 2;
    case DONT_SCANNED = 3;

    public static function calculateStatus(bool $proved, bool $startedScan, string $type): self
    {
        $status = $proved ? InOrderStatus::PROVED : InOrderStatus::UNPROVED;

        if (!$proved && !$startedScan) {
            $status = InOrderStatus::DONT_SCANNED;
        }

        return $status;
    }
}
