<?php

namespace App\Enums;


enum InOrderStatus: int
{

    case PROVED = 1;
    case UNPROVED = 2;
    case DONT_SCANNED = 3;

}
