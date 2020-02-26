<?php
declare(strict_types=1);

namespace App\Domain\Payment;


use App\Domain\Order\Order;
use Money\Money;

interface PaymentService
{
    public function pay(Order $order, Money $userMoney): void;
}
