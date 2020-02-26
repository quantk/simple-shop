<?php
declare(strict_types=1);

namespace App\Domain\Order;


use Ramsey\Uuid\Uuid;

interface Orders
{
    public function add(Order $order): void;

    public function addOrderLine(OrderLine $orderLine): void;

    public function find(Uuid $uid): ?Order;
}
