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
$router->register('GET', null, 'postController', 'home');
$router->register('GET', 'posts', 'postController', 'showPostsPage');
$router->register('GET', 'post', 'postController', 'showSinglePost');
$router->register('GET', 'login', 'accountController', 'showLoginPage');
$router->register('GET', 'signin', 'accountController', 'showSigninPage');
$router->register('POST', null, 'accountController', 'contactForm');
$router->register('POST', 'signin', 'accountController', 'signinForm');

// Back
$router->register('GET', 'posts', 'backController', 'showPostsManager');

$router->routerRequest();
