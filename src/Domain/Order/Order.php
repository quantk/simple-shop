<?php
declare(strict_types=1);

namespace App\Domain\Order;


use App\Domain\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $status;

    /**
     * @ORM\Column(name="user_id", type="integer")
     */
    private int $userId;

    /**
     * @var OrderLine[]|Collection
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Order\OrderLine",
     *     mappedBy="order",
     *     cascade={ "persist", "remove" },
     *     orphanRemoval=TRUE,
     * )
     */
    private Collection $orderLines;

    /**
     * @ORM\Column(name="created_at", type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    public function __construct(Uuid $uid, User $user, string $status = OrderModel::STATUS_NEW)
    {
        $this->setStatus($status);

        $this->id     = $uid;
        $this->userId = $user->id();

        $this->createdAt  = new \DateTimeImmutable();
        $this->orderLines = new ArrayCollection();
    }

    public function addOrderLine(OrderLine $orderLine): void
    {
        $this->orderLines->add($orderLine);
    }

    public function markPaid(): void
    {
        $this->setStatus(OrderModel::STATUS_PAID);
    }

    /**
     * @param string $status
     */
    private function setStatus(string $status): void
    {
        if (in_array($status, OrderModel::AVAILABLE_STATUSES, true) === false) {
            throw new \DomainException('Invalid status');
        }
        $this->status = $status;
    }

    public function getAmount(): Money
    {
        $money = Money::RUB(0);
        /** @var OrderLine $orderLine */
        foreach ($this->orderLines as $orderLine) {
            $money = $orderLine->addPriceToMoney($money);
        }

        return $money;
    }

    public function idAsString(): string
    {
        return (string)$this->id;
    }
}
