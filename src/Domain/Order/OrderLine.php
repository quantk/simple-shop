<?php
declare(strict_types=1);

namespace App\Domain\Order;


use App\Domain\Product\Product;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_lines")
 */
final class OrderLine
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Domain\Product\Product", inversedBy="orders")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private Product $product;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Domain\Order\Order", inversedBy="products")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private Order $order;

    /**
     * @ORM\Column(type="string")
     */
    private string $price;

    /**
     * @ORM\Column(type="integer")
     */
    private int $count;

    public function __construct(Product $product, Order $order, int $count)
    {
        $this->product = $product;
        $this->order   = $order;
        $this->price   = $product->getPrice()->getAmount();
        $this->count   = $count;
    }

    public function addPriceToMoney(Money $money): Money
    {
        return $money->add(Money::RUB($this->price));
    }
}
