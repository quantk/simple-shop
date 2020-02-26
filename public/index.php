<?php
declare(strict_types=1);

use FMW\Application;
use Symfony\Component\HttpFoundation\Request;
use function FMW\handleFatal;
use function FMW\prepareRequest;

try {
    /** @var Application $app */
    $app      = require __DIR__ . '/../framework/bootstrap.php';
    $request  = prepareRequest(Request::createFromGlobals());
    $response = $app->run($request);
    $response->send();
} catch (\Throwable $e) {
    handleFatal($e);
}
