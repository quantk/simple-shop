<?php
declare(strict_types=1);

namespace App\Domain\Product;


use Ramsey\Uuid\Uuid;

interface Products
{
    public function add(Product $order): void;

    public function find(Uuid $id): ?Product;

    /**
     * @param array|Uuid[] $uids
     * @return array|Product[]
     */
    public function findByUids(array $uids): array;
}
