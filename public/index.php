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
$router->register('GET', '/', 'postController', 'home');//                     /
$router->register('POST', '/', 'accountController', 'contactForm');//          /
$router->register('GET', '/posts/[i:postPage]?', 'postController', 'showPostsPage'); //        /posts/$postsPage
$router->register('GET', '/post/[i:postId]/[i:commentPage]?', 'postController', 'showSinglePost');// /post/$postId/$commentPage
$router->register('POST', '/addcomment/[i:postId]', 'postController', 'addComment');//      /addcomment/$postId
$router->register('GET', '/account/login', 'accountController', 'showLoginPage');//      /account/login
$router->register('POST', '/account/login', 'accountController', 'loginForm');//         /account/login
$router->register('GET', '/account/signin', 'accountController', 'showSigninPage');//    /account/signin
$router->register('POST', '/account/signin', 'accountController', 'signinForm');//       /account/signin

// Back
$router->register('GET', '/admin/posts/[i:postPage]?', 'backController','showPostsManager');//      /admin/posts/$postsPage
$router->register('GET', '/admin/post/[i:postId]', 'backController', 'showEditPost');//           /admin/post/$postId
$router->register('POST', '/admin/post/[i:postId]', 'backController', 'modifyPost'); //           /admin/post/$postId
$router->register('GET', '/admin/newpost', 'backController', 'showAddPost');//         /admin/newpost
$router->register('POST', '/admin/newpost', 'backController', 'addPost');//            /admin/newpost
$router->register('GET', '/admin/delete/[i:postId]', 'backController', 'delete');//               /admin/delete/$postId
$router->register('GET', '/admin/comments/[i:commentPage]?', 'backController', 'showCommentsManager');///admin/comments/$commentPage
$router->register('GET', '/admin/approve/[i:commentId]', 'backController', 'approve');//             /admin/approve/$commentId
$router->register('GET', '/admin/refuse/[i:commentId]', 'backController', 'refuse');//               /admin/refuse/$commentId
$router->register('GET', '/admin/members/[i:membersPage]?', 'backController', 'showUsersManager');//    /admin/members/$membersPage
$router->register('GET', '/admin/promote/[i:userId]', 'backController', 'promote');//             /admin/promote/$userId
$router->register('GET', '/admin/demote/[i:userId]', 'backController', 'demote');//               /admin/demote/$userId

// Error
$router->register('GET', '/404', 'errorController', 'show404');//                /404

$match = $router->match();
if ($match === false){
    $match['controller'] = 'errorController';
    $match['action'] = 'show404';
}

$router->routerRequest($match['controller'], $match['action']);
