<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controller\Router;

$router = new Router();
$router->routerRequest();
