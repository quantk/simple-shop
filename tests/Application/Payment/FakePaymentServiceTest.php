<?php
declare(strict_types=1);

namespace Tests\Application\Payment;


use App\Application\Payment\FakePaymentService;
use App\Application\Payment\PaymentProcessor;
use App\Domain\Order\Order;
use App\Domain\Order\OrderLine;
use App\Domain\Order\OrderModel;
use App\Domain\Product\Product;
use App\Domain\User;
use Money\Money;
use Tests\TestCase;
use function FMW\uid;

final class FakePaymentServiceTest extends TestCase
{
    public function testPayWithLowMoney()
    {
        $paymentProcessor = $this->createMock(PaymentProcessor::class);

        $price   = Money::RUB(50000);
        $product = new Product(uid(), 'title', $price);
        $order   = new Order(uid(), User::create());
        $order->addOrderLine(new OrderLine($product, $order, 1));

        $paymentService = new FakePaymentService($paymentProcessor);
        $this->expectException(\DomainException::class);
        $paymentService->pay($order, $price->subtract(Money::RUB(100)));
        static::assertSame(self::getObjectPropertyValue($order, 'status'), OrderModel::STATUS_NEW);
    }

    public function testPay()
    {
        $paymentProcessor = $this->createMock(PaymentProcessor::class);

        $price   = Money::RUB(50000);
        $product = new Product(uid(), 'title', $price);
        $order   = new Order(uid(), User::create());
        $order->addOrderLine(new OrderLine($product, $order, 1));

        $paymentService = new FakePaymentService($paymentProcessor);
        $paymentService->pay($order, Money::RUB(50000));
        static::assertSame(self::getObjectPropertyValue($order, 'status'), OrderModel::STATUS_PAID);
    }
}
