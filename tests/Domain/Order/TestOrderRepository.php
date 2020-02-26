<?php
declare(strict_types=1);

namespace Tests\Domain\Order;


use App\Domain\Order\Order;
use App\Domain\Order\OrderLine;
use App\Domain\Order\Orders;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

final class TestOrderRepository implements Orders
{
    private ArrayCollection $orders;
    private ArrayCollection $orderLines;

    /**
     * TestProductRepository constructor.
     */
    public function __construct()
    {
        $this->orders     = new ArrayCollection();
        $this->orderLines = new ArrayCollection();
    }


    public function add(Order $order): void
    {
        $this->orders->set($order->idAsString(), $order);
    }

    public function find(Uuid $id): ?Order
    {
        return $this->orders->get((string)$id);
    }

    public function addOrderLine(OrderLine $orderLine): void
    {
        $this->orderLines->add($orderLine);
    }
}
