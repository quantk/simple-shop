<?php
declare(strict_types=1);

namespace App\Domain\Order;


final class OrderModel
{
    public const STATUS_NEW  = 'new';
    public const STATUS_PAID = 'paid';

    public const AVAILABLE_STATUSES = [
        self::STATUS_NEW,
        self::STATUS_PAID
    ];
}
