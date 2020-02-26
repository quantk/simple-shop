<?php
declare(strict_types=1);

use DI\Container;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Ramsey\Uuid\Doctrine\UuidBinaryType;
use Ramsey\Uuid\Doctrine\UuidType;

return [
    EntityManagerInterface::class => static function (Container $container) {
        $dbuser     = getenv('DB_USER');
        $dbpassword = getenv('DB_PASSWORD');
        $dbname     = getenv('DB_NAME');

        $paths                     = [__DIR__ . '/../../src/Domain'];
        $isDevMode                 = true;
        $proxyDir                  = null;
        $cache                     = null;
        $useSimpleAnnotationReader = false;

        $dbParams = [
            'driver'   => 'pdo_mysql',
            'user'     => $dbuser,
            'password' => $dbpassword,
            'dbname'   => $dbname,
        ];

        Type::addType('uuid', UuidType::class);
        Type::addType('uuid_binary', UuidBinaryType::class);

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
        return EntityManager::create($dbParams, $config);
    },
];
