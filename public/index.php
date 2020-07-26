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
$router->register('GET', '/', '\FrontOffice\PostController', 'home');//                                          /
$router->register('POST', '/', '\FrontOffice\AccountController', 'contactForm');//                               /
$router->register('GET', '/posts/[0-9]', '\FrontOffice\PostController', 'showPostsPage'); //                     /posts/$postsPage
$router->register('GET', '/post/[0-9]/[0-9]', '\FrontOffice\PostController', 'showSinglePost');//                /post/$postId/$commentPage
$router->register('POST', '/addcomment/[0-9]', '\FrontOffice\PostController', 'addComment');//                   /addcomment/$postId
$router->register('GET', '/account/login', '\FrontOffice\AccountController', 'showLoginPage');//                 /account/login
$router->register('GET', '/account/logout', '\FrontOffice\AccountController', 'logout');     //                  /account/logout
$router->register('POST', '/account/login', '\FrontOffice\AccountController', 'loginForm');//                    /account/login
$router->register('GET', '/account/signin', '\FrontOffice\AccountController', 'showSigninPage');//               /account/signin
$router->register('POST', '/account/signin', '\FrontOffice\AccountController', 'signinForm');//                  /account/signin
$router->register('GET', '/account/activate/[token]', '\FrontOffice\AccountController', 'activate');//           /account/activate/
$router->register('GET', '/account/resendsignin', '\FrontOffice\AccountController', 'resendMail');//             /account/resendsignin/

// Back
$router->register('GET', '/admin/posts/[0-9]', '\BackOffice\AdminController','showPostsManager');//         /admin/posts/$postsPage
$router->register('GET', '/admin/post/[0-9]', '\BackOffice\AdminController', 'showEditPost');//             /admin/post/$postId
$router->register('POST', '/admin/post/[0-9]', '\BackOffice\AdminController', 'modifyPost'); //             /admin/post/$postId
$router->register('GET', '/admin/newpost', '\BackOffice\AdminController', 'showAddPost');//                 /admin/newpost
$router->register('POST', '/admin/newpost', '\BackOffice\AdminController', 'addPost');//                    /admin/newpost
$router->register('GET', '/admin/delete/[0-9]', '\BackOffice\AdminController', 'delete');//                 /admin/delete/$postId
$router->register('GET', '/admin/comments/[0-9]', '\BackOffice\AdminController', 'showCommentsManager');// /admin/comments/$commentPage
$router->register('GET', '/admin/approve/[0-9]', '\BackOffice\AdminController', 'approve');//              /admin/approve/$commentId
$router->register('GET', '/admin/refuse/[0-9]', '\BackOffice\AdminController', 'refuse');//                /admin/refuse/$commentId
$router->register('GET', '/super/members/[0-9]', '\BackOffice\SuperAdminController', 'showUsersManager');//  /super/members/$membersPage
$router->register('GET', '/super/promote/[0-9]', '\BackOffice\SuperAdminController', 'promote');//           /super/promote/$userId
$router->register('GET', '/super/demote/[0-9]', '\BackOffice\SuperAdminController', 'demote');//             /super/demote/$userId

// Error
$router->register('GET', '/404', '\ErrorController', 'show404');//                                          /404

$match = $router->match();
if ($match === null){
    $match['controller'] = 'errorController';
    $match['action'] = 'show404';
}

$router->routerRequest($match['controller'], $match['action']);
