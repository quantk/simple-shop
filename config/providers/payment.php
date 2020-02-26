<?php
declare(strict_types=1);

use App\Application\Payment\FakePaymentService;
use App\Application\Payment\PaymentProcessor;
use App\Domain\Payment\PaymentService;
use DI\Container;
use GuzzleHttp\ClientInterface;

return [
    PaymentService::class   => static function (Container $container) {
        return $container->get(FakePaymentService::class);
    },
    PaymentProcessor::class => static function (Container $container) {
        return new PaymentProcessor(getenv('PAYMENT_SERVICE_URL'), $container->get(ClientInterface::class));
    }
];
