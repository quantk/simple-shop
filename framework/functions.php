<?php
declare(strict_types=1);

namespace FMW;

use DI\Container;
use DI\ContainerBuilder;
use FastRoute\Dispatcher;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

function uid(): Uuid
{
    /** @var Uuid $uuid */
    $uuid = Uuid::uuid4();
    return $uuid;
}

function uidFromString(string $uid): Uuid
{
    /** @var Uuid $uuid */
    $uuid = Uuid::fromString($uid);
    return $uuid;
}

function handleFatal(\Throwable $e): void
{
    $env = getenv('APP_ENV');
    if ($env === Application::PRODUCTION_MODE) {
        $response = new JsonResponse(['errors' => ['server error']], 500);
    } else {
        $response = new JsonResponse(['errors' => [(string)$e]]);
    }

    $response->send();
}

function prepareRequest(Request $request): Request
{
    /** @var string|null $content */
    $content = $request->getContent();
    if ('json' === $request->getContentType() && $content !== null) {
        /** @var array|mixed $data */
        $data = \json_decode($content, true);

        if (\json_last_error() !== \JSON_ERROR_NONE) {
            return $request;
        }

        if (\is_array($data)) {
            $request->request->replace($data);
        }
    }

    return $request;
}

function buildRouter(): Dispatcher
{
    /** @var Dispatcher $dispatcher */
    $dispatcher = require __DIR__ . '/../config/routes.php';
    return $dispatcher;
}

/**
 * @return Container
 * @noinspection PhpDocMissingThrowsInspection
 */
function buildContainer(): Container
{
    $builder = new ContainerBuilder();
    $builder->useAnnotations(false);
    $builder->useAnnotations(true);

    if (Application::PRODUCTION_MODE === getenv('APP_ENV')) {
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
        ini_set('error_log', 'syslog');

        $builder->enableCompilation(__DIR__ . '/../var/di');
        $builder->writeProxiesToFile(true, __DIR__ . '/../di/proxies');
    }

    $services = new Finder();
    $services->files()->in(__DIR__ . '/../config/providers');

    $definitions = [];
    /** @var string $service */
    foreach ($services as $service) {
        /** @noinspection UsingInclusionOnceReturnValueInspection */
        /** @noinspection SlowArrayOperationsInLoopInspection */
        /** @psalm-suppress UnresolvableInclude */
        $definitions = array_merge($definitions, require_once $service);
    }

    $builder->addDefinitions($definitions);

    /** @var Container $container */
    /** @noinspection PhpUnhandledExceptionInspection */
    return $builder->build();
}
