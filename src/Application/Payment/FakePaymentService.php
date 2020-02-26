<?php
declare(strict_types=1);

namespace App\Application\Payment;


use App\Domain\Order\Order;
use App\Domain\Payment\PaymentService;
use Money\Money;

final class FakePaymentService implements PaymentService
{
    private PaymentProcessor $paymentProcessor;

    public function __construct(PaymentProcessor $paymentProcessor)
    {
        $this->paymentProcessor = $paymentProcessor;
    }

    public function pay(Order $order, Money $userMoney): void
    {
        $orderPrice = $order->getAmount();
        if ($orderPrice->equals($userMoney) === false) {
            throw new \DomainException('User money not equals of order price');
        }

        $this->paymentProcessor->process();

        $order->markPaid();
    }
}
