<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require_once __DIR__.'/../bootstrap/app.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);

