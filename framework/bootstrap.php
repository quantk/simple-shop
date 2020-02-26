<?php
declare(strict_types=1);


use FMW\Application;
use function FMW\buildContainer;
use function FMW\buildRouter;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

return new Application(buildRouter(), buildContainer());
