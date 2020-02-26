<?php
declare(strict_types=1);

use DI\Container;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

return [
    LoggerInterface::class => static function (Container $container) {
        $log         = new Logger('name');
        $loggerPath  = $container->get('logger.path');
        $loggerLevel = $container->get('logger.level') ?? Logger::WARNING;
        $handler     = $loggerPath !== null ? new StreamHandler($loggerPath, $loggerLevel) : new NullHandler($loggerLevel);
        $log->pushHandler($handler);

        return $log;
    },
    'logger.path'          => __DIR__ . '/../../var/log/all.log',
    'logger.level'         => Logger::WARNING
];
