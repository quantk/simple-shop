<?php
declare(strict_types=1);

namespace App\Domain\Product;


use Money\Money;
use Ramsey\Uuid\Uuid;

final class ProductCreator
{
    private Products $productRepository;

    public function __construct(Products $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create(Uuid $uid, string $title, Money $price): Product
    {
        $product = new Product($uid, $title, $price);
        $this->productRepository->add($product);

        return $product;
    }
}
