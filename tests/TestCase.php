<?php
declare(strict_types=1);

namespace Tests;


class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function getObjectPropertyValue($object, string $property)
    {
        /** @noinspection PhpFullyQualifiedNameUsageInspection */
        /** @noinspection PhpUnhandledExceptionInspection */
        $r = new \ReflectionClass($object);
        /** @noinspection PhpUnhandledExceptionInspection */
        $property = $r->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
