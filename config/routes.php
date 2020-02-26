<?php
declare(strict_types=1);

use App\Application\Order\OrderController;
use App\Application\Payment\PaymentController;
use FastRoute\RouteCollector;
use FMW\RouteHandler;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(static function (RouteCollector $collector) {
    $collector->addGroup('/api', static function (RouteCollector $collector) {
        $collector->addGroup('/order', static function (RouteCollector $collector) {
            $collector->post('/create', RouteHandler::byController(OrderController::class, 'create'));
            $collector->post('/{orderId}/pay', RouteHandler::byController(PaymentController::class, 'pay'));
        });
    });
});
