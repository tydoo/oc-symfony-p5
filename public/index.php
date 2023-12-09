<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Throwable;
use App\Kernel;
use App\Controller\ErrorController;

try {
    $kernel = new Kernel();
    $kernel->run();
} catch (Throwable $th) {
    $error = new ErrorController();
    $error->internalServerError();
}
