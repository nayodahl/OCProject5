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
$router->register('POST', 'addcomment', 'postController', 'addComment');

// Back
$router->register('GET', 'posts', 'backController', 'showPostsManager');
$router->register('GET', 'post', 'backController', 'showEditPost');
$router->register('POST', 'post', 'backController', 'modifyPost'); 
$router->register('GET', 'newpost', 'backController', 'showAddPost');
// TO DO  $router->register('POST', 'newpost', 'backController', 'addPost');
// TO DO  $router->register('GET', 'delete', 'backController', 'delete');
$router->register('GET', 'comments', 'backController', 'showCommentsManager');
$router->register('GET', 'members', 'backController', 'showUsersManager');
// TO DO  $router->register('GET', 'promote', 'backController', '');
// TO DO  $router->register('GET', 'demote', 'backController', '');
$router->register('GET', 'approve', 'backController', 'approve');
$router->register('GET', 'refuse', 'backController', 'refuse');

// Error
$router->register('GET', '404', 'errorController', 'show404');

$router->routerRequest();
