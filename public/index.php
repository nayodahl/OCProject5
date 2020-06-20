<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use App\Service\Router;

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$router = new Router();


// Routes
//  Front
$router->register('GET', '', 'frontController', 'home');
$router->register('GET', 'posts', 'frontController', 'showPostsPage');
$router->register('GET', 'post', 'frontController', 'showSinglePost');
$router->register('GET', 'login', 'frontController', 'showLoginPage');
$router->register('GET', 'signin', 'frontController', 'showSigninPage');


$router->routerRequest();
