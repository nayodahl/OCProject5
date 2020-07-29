<?php
declare(strict_types=1);

namespace App\Service;

use \App\Service\Http\Request;
use \App\View\View;
use \App\Service\Http\Session;
use \App\Service\Config;
use \App\Service\Auth;
use \App\Service\Database;
use \App\Controller\FrontOffice\PostController;
use \App\Controller\FrontOffice\AccountController;
use \App\Controller\BackOffice\AdminController;
use \App\Controller\BackOffice\SuperAdminController;
use \App\Controller\ErrorController;
use \App\Model\Repository\PostRepository;
use \App\Model\Manager\PostManager;
use \App\Model\Repository\CommentRepository;
use \App\Model\Manager\CommentManager;
use \App\Model\Repository\UserRepository;
use \App\Model\Manager\UserManager;

class Router
{
    private $routes;
    private $request;
    private $renderer;
    private $session;
    private $config;
    private $auth;
    private $database;
    private $postRepo;
    private $commentRepo;
    private $userRepo;
    private $postManager;
    private $commentManager;
    private $userManager;
    private $postController;
    private $accountController;
    private $adminController;
    private $superAdminController;
    private $errorController;
    
    public function __construct()
    {
        // dependancies
        $this->request = new Request();
        $this->renderer = new View();
        $this->session = new Session();
        $this->config = new Config();

        // injecting dependancies
        $this->database = new Database($this->config);
        $this->userRepo = new UserRepository($this->database);
        $this->postRepo = new PostRepository($this->database);
        $this->commentRepo = new CommentRepository($this->database);
        $this->auth = new Auth($this->session, $this->userRepo);
        $this->postManager = new PostManager($this->postRepo, $this->session);
        $this->commentManager = new CommentManager($this->commentRepo, $this->session);
        $this->userManager= new UserManager($this->userRepo, $this->session);
        $this->postController = new PostController($this->postManager, $this->commentManager, $this->renderer, $this->session, $this->auth);
        $this->accountController = new AccountController($this->postManager, $this->commentManager, $this->userManager, $this->renderer, $this->session, $this->auth, $this->config);
        $this->adminController = new AdminController($this->postManager, $this->commentManager, $this->userManager, $this->renderer, $this->session, $this->auth);
        $this->superAdminController = new SuperAdminController($this->userManager, $this->renderer, $this->session, $this->auth);
        $this->errorController = new ErrorController($this->renderer, $this->session, $this->auth);
    }

    // register all routes
    public function register(string $method, string $route, string $controller, string $action): void
    {
        $route = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'action' => $action
        ];
        $this->routes[] = $route;
    }

    // check if a route matches the requestUrl, using regex
    public function match(): ?array
    {
        // set Request Url
        $requestUrl = "/";
        if ($this->request->getGet()) {
            $requestUrl = "/" . implode('/', $this->request->getGet());
        }
        
        // set Request Method
        $requestMethod='GET';
        if ($this->request->getPost()) {
            $requestMethod='POST';
        };

        $lastRequestUrlChar = $requestUrl[mb_strlen($requestUrl)-1];
        
        foreach ($this->routes as $route) {
            $match = $methodMatch = false;
            $methodMatch = (mb_stripos($route['method'], $requestMethod) !== false);

            // Method did not match, continue to next route.
            if (!$methodMatch) {
                continue;
            }
 
            if (($position = mb_strpos($route['route'], '[')) === false) {
                // No params in url, do string comparison
                $match = strcmp($requestUrl, $route['route']) === 0;
            } else {
                // Compare longest non-param string with url before moving on to regex
                // Check if last character before param is a slash, because it could be optional if param is optional too
                if (strncmp($requestUrl, $route['route'], $position) !== 0 && ($lastRequestUrlChar === '/' || $route['route'][$position-1] !== '/')) {
                    continue;
                }
                
                // when request param in route is an int, we set this regex
                if (preg_match('/[0-9]/', $route['route']) === 1) {
                    $regex1 = mb_substr($route['route'], 0, mb_strpos($route['route'], '[')) . '[0-9]{1,}/?[0-9]?';
                    $regex2 = "`^$regex1$`u";
                    $match = preg_match($regex2, $requestUrl) === 1;
                }

                // only case when param is a token and not an int, then we change the regex
                if (preg_match('/token/', $route['route']) === 1) {
                    $regex="#^/account/activate/[0-9a-zA-Z]{32}$#";
                    $match = preg_match($regex, $requestUrl) === 1;
                }
            }

            if ($match) {
                return [
                    'controller' => $route['controller'],
                    'action' => $route['action']
                ];
            }
        }

        return null;
    }
    
    // Routing entry request, and calling the needed controller on demand
    public function routerRequest($controller, $action): void
    {
        if (property_exists($this, $controller)) {
            call_user_func_array([$this->{$controller}, $action], [$this->request]);
            exit();
        }
        // else 404
        call_user_func([$this->errorController, 'show404']);
    }
}
