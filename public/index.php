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
$router->register('GET', null, 'postController', 'home');//                     /
$router->register('POST', null, 'accountController', 'contactForm');//          /
$router->register('GET', 'posts', 'postController', 'showPostsPage'); //        /posts/$postsPage
$router->register('GET', 'post', 'postController', 'showSinglePost');//         /post/$postId/$commentPage
$router->register('POST', 'addcomment', 'postController', 'addComment');//      /post/$postId/$commentPage
$router->register('GET', 'login', 'accountController', 'showLoginPage');//      /account/login
$router->register('POST', 'login', 'accountController', 'loginForm');//         /account/login
$router->register('GET', 'signin', 'accountController', 'showSigninPage');//    /account/signin
$router->register('POST', 'signin', 'accountController', 'signinForm');//       /account/signin

// Back
$router->register('GET', 'posts', 'backController', 'showPostsManager');//      /admin/posts/$postsPage
$router->register('GET', 'post', 'backController', 'showEditPost');//           /admin/post/$postId
$router->register('POST', 'post', 'backController', 'modifyPost'); //           /admin/post/$postId
$router->register('GET', 'newpost', 'backController', 'showAddPost');//         /admin/newpost
$router->register('POST', 'newpost', 'backController', 'addPost');//            /admin/newpost
$router->register('GET', 'delete', 'backController', 'delete');//               /admin/delete/$postId
$router->register('GET', 'comments', 'backController', 'showCommentsManager');///admin/comments/$commentPage
$router->register('GET', 'approve', 'backController', 'approve');//             /admin/approve/$commentId
$router->register('GET', 'refuse', 'backController', 'refuse');//               /admin/refuse/$commentId
$router->register('GET', 'members', 'backController', 'showUsersManager');//    /admin/members
$router->register('GET', 'promote', 'backController', 'promote');//             /admin/promote/$userId
$router->register('GET', 'demote', 'backController', 'demote');//               /admin/demote/$userId

// Error
$router->register('GET', '404', 'errorController', 'show404');//                /404

$router->routerRequest();
