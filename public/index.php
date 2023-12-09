<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Kernel;

$kernel = new Kernel();
$kernel->run();
