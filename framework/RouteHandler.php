<?php
declare(strict_types=1);

namespace FMW;


use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class RouteHandler
{
    public const CALLABLE_TYPE   = 'callable';
    public const CONTROLLER_TYPE = 'controller';

    /**
     * @var callable|array{'class': string, 'method': string} $handler
     */
    private $handler;

    private string $type;

    /**
     * RouteHandler constructor.
     * @param callable|array{class: string, method: string} $handler
     * @param string $type
     */
    private function __construct($handler, string $type)
    {
        /** @psalm-suppress PossiblyInvalidPropertyAssignmentValue */
        $this->handler = $handler;
        $this->type    = $type;
    }

    public static function byCallable(callable $handler): self
    {
        return new static ($handler, self::CALLABLE_TYPE);
    }

    public static function byController(string $class, string $method): self
    {
        return new static(['class' => $class, 'method' => $method], self::CONTROLLER_TYPE);
    }

    /**
     * @param array $parameters
     * @param Container $container
     * @return Response
     * @throws DependencyException
     * @throws NotFoundException
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArgument
     */
    public function handle(array $parameters, Container $container): Response
    {
        if ($this->type === self::CALLABLE_TYPE) {
            /** @var callable $callable */
            $callable = $this->handler;
            /** @var Response $response */
            $response = $container->call($callable, $parameters);
        } else {
            ['class' => $class, 'method' => $method] = $this->handler;
            $controller = $container->get($class);
            /** @var Response $response */
            $response = $container->call([$controller, $method], $parameters);
        }

        return $response;
    }
}
