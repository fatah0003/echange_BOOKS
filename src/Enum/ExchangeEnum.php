<?php

namespace App\Enum;

enum ExchangeEnum: string
{
    case PENDING = 'pending';
    case VALIDATED = 'validated';
    case REJECTED = 'rejected';
}
