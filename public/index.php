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
$router->register('GET', '/', 'postController', 'home');//                                          /
$router->register('POST', '/', 'accountController', 'contactForm');//                               /
$router->register('GET', '/posts/[0-9]', 'postController', 'showPostsPage'); //                     /posts/$postsPage
$router->register('GET', '/post/[0-9]/[0-9]', 'postController', 'showSinglePost');//                /post/$postId/$commentPage
$router->register('POST', '/addcomment/[0-9]', 'postController', 'addComment');//                   /addcomment/$postId
$router->register('GET', '/account/login', 'accountController', 'showLoginPage');//                 /account/login
$router->register('GET', '/account/logout', 'accountController', 'logout');     //                 /account/logout
$router->register('POST', '/account/login', 'accountController', 'loginForm');//                    /account/login
$router->register('GET', '/account/signin', 'accountController', 'showSigninPage');//               /account/signin
$router->register('POST', '/account/signin', 'accountController', 'signinForm');//                  /account/signin
$router->register('GET', '/account/activate/[token]', 'accountController', 'activate');//           /account/activate/

// Back
$router->register('GET', '/admin/posts/[0-9]', 'adminController','showPostsManager');//          /admin/posts/$postsPage
$router->register('GET', '/admin/post/[0-9]', 'adminController', 'showEditPost');//             /admin/post/$postId
$router->register('POST', '/admin/post/[0-9]', 'adminController', 'modifyPost'); //             /admin/post/$postId
$router->register('GET', '/admin/newpost', 'adminController', 'showAddPost');//                      /admin/newpost
$router->register('POST', '/admin/newpost', 'adminController', 'addPost');//                         /admin/newpost
$router->register('GET', '/admin/delete/[0-9]', 'adminController', 'delete');//                 /admin/delete/$postId
$router->register('GET', '/admin/comments/[0-9]', 'adminController', 'showCommentsManager');///admin/comments/$commentPage
$router->register('GET', '/admin/approve/[0-9]', 'adminController', 'approve');//             /admin/approve/$commentId
$router->register('GET', '/admin/refuse/[0-9]', 'adminController', 'refuse');//               /admin/refuse/$commentId
$router->register('GET', '/super/members/[0-9]', 'superAdminController', 'showUsersManager');//       /super/members/$membersPage
$router->register('GET', '/super/promote/[0-9]', 'superAdminController', 'promote');//               /super/promote/$userId
$router->register('GET', '/super/demote/[0-9]', 'superAdminController', 'demote');//                 /super/demote/$userId

// Error
$router->register('GET', '/404', 'errorController', 'show404');//                                   /404

$match = $router->match();
if ($match === null){
    $match['controller'] = 'errorController';
    $match['action'] = 'show404';
}

$router->routerRequest($match['controller'], $match['action']);
