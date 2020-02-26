<?php
declare(strict_types=1);

namespace App\Domain\Order;


use App\Domain\Product\Product;
use App\Domain\User;
use Ramsey\Uuid\Uuid;

final class OrderCreator
{
    private Orders $orderRepository;

    public function __construct(Orders $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function create(Uuid $orderId, User $user, Product ...$products): Order
    {
        if (count($products) === 0) {
            throw new \DomainException('Cant create order with zero products');
        }

        $order = new Order($orderId, $user);
        $this->orderRepository->add($order);

        foreach ($products as $product) {
            $orderLine = new OrderLine($product, $order, 1);
            $this->orderRepository->addOrderLine($orderLine);
            $order->addOrderLine($orderLine);
        }

        return $order;
    }
}
