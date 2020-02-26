<?php
declare(strict_types=1);

namespace App\Domain\Product;


use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(name="products")
 */
class Product
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
    private string $title;

    /**
     * @ORM\Column(name="created_at", type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $price;

    public function __construct(Uuid $uid, string $title, Money $price)
    {
        $this->id        = $uid;
        $this->title     = $title;
        $this->price     = $price->getAmount();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getPrice(): Money
    {
        return Money::RUB($this->price);
    }

    public function idAsString(): string
    {
        return (string)$this->id;
    }
}
