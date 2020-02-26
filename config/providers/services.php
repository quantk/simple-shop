<?php
declare(strict_types=1);

use App\Application\Order\OrderRepository;
use App\Application\Product\ProductRepository;
use App\Domain\Order\Orders;
use App\Domain\Product\Products;
use DI\Container;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

return [
    Orders::class          => static function (Container $container) {
        return $container->get(OrderRepository::class);
    },
    Products::class        => static function (Container $container) {
        return $container->get(ProductRepository::class);
    },
    ClientInterface::class => static function (Container $container) {
        return new Client();
    }
];
