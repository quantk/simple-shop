<?php
declare(strict_types=1);

namespace Tests\Domain\Order;


use App\Domain\Order\OrderCreator;
use App\Domain\Product\Product;
use App\Domain\User;
use Money\Money;
use Tests\TestCase;
use function FMW\uid;

final class OrderCreatorTest extends TestCase
{
    public function testCreateWithoutProducts()
    {
        $orderRepository = new TestOrderRepository();
        $orderCreator    = new OrderCreator($orderRepository);

        $orderId = uid();
        $this->expectException(\DomainException::class);
        $orderCreator->create($orderId, User::create());
    }

    public function testCreate()
    {
        $product = new Product(uid(), 'title', Money::RUB(50000));

        $orderRepository = new TestOrderRepository();
        $orderCreator    = new OrderCreator($orderRepository);

        $orderId = uid();
        $order   = $orderCreator->create($orderId, User::create(), $product);
        static::assertSame((string)$orderId, $order->idAsString());
        static::assertSame($product->getPrice()->getAmount(), $order->getAmount()->getAmount());
        static::assertSame($product->getPrice()->getCurrency()->getCode(), $order->getAmount()->getCurrency()->getCode());
    }
}
