<?php
declare(strict_types=1);

namespace Tests\Domain\Product;


use App\Domain\Product\Product;
use App\Domain\Product\Products;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

final class TestProductRepository implements Products
{
    private ArrayCollection $products;

    /**
     * TestProductRepository constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }


    public function add(Product $order): void
    {
        $this->products->set($order->idAsString(), $order);
    }

    public function find(Uuid $id): ?Product
    {
        return $this->products->get((string)$id);
    }

    /**
     * @inheritDoc
     */
    public function findByUids(array $uids): array
    {
        $products = [];
        foreach ($uids as $uid) {
            $product = $this->products->get((string)$uid);
            if ($product !== null) {
                $products[] = $product;
            }
        }

        return $products;
    }
}
