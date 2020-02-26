<?php
declare(strict_types=1);

namespace App\Application\Order;


use App\Domain\Order\Order;
use App\Domain\Order\OrderLine;
use App\Domain\Order\Orders;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

final class OrderRepository implements Orders
{
    private EntityManagerInterface $entityManager;
    private EntityRepository       $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        /** @var EntityRepository $repository */
        $repository       = $entityManager->getRepository(Order::class);
        $this->repository = $repository;
    }

    public function add(Order $order): void
    {
        $this->entityManager->persist($order);
    }

    public function addOrderLine(OrderLine $orderLine): void
    {
        $this->entityManager->persist($orderLine);
    }

    public function find(Uuid $uid): ?Order
    {
        /** @var Order|null $order */
        $order = $this->repository->find($uid);
        return $order;
    }
}
