<?php
declare(strict_types=1);

namespace FMW;


use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use FastRoute\Dispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Application
{
    public const PRODUCTION_MODE = 'production';
    private Dispatcher $router;
    private Container  $container;

    /**
     * Application constructor.
     * @param Dispatcher $router
     * @param Container $container
     */
    public function __construct(Dispatcher $router, Container $container)
    {
        $this->router    = $router;
        $this->container = $container;
    }

    public function run(Request $request): Response
    {
        $this->container->set(Request::class, $request);
        $routeInfo = $this->router->dispatch($request->getMethod(), $request->getPathInfo());
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return new Response('/404', 404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                return new Response('/405', 405);
                break;
            case Dispatcher::FOUND:
                /** @var array $vars */
                [, $handler, $vars] = $routeInfo;

                /** @var RouteHandler $handler */
                return $this->executeHandler($handler, $vars);
                break;
        }

        throw new \RuntimeException('Server error');
    }

    /**
     * @param string $key
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function resolveDependency(string $key)
    {
        return $this->container->get($key);
    }

    private function executeHandler(RouteHandler $handler, array $parameters = []): Response
    {
        return $handler->handle($parameters, $this->container);
    }
}
