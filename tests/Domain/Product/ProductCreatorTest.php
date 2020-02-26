<?php
declare(strict_types=1);

namespace Tests\Domain\Product;


use App\Domain\Product\ProductCreator;
use Money\Money;
use Tests\TestCase;
use function FMW\uid;

final class ProductCreatorTest extends TestCase
{
    public function testCreate(): void
    {
        $productRepository = new TestProductRepository();
        $productCreator    = new ProductCreator($productRepository);
        $uid               = uid();

        $money   = Money::RUB(50000);
        $title   = 'test title';
        $product = $productCreator->create($uid, $title, $money);

        static::assertSame((string)$uid, $product->idAsString());
        static::assertSame(self::getObjectPropertyValue($product, 'title'), $title);
        static::assertSame($product->getPrice()->getAmount(), $money->getAmount());
    }
}
