<?php
declare(strict_types=1);

namespace App\Service;

use \App\Controller\FrontController;
use \App\Controller\BackController;
use \App\Service\Http\Request;

class Router
{
    private $frontController;
    private $backController;
    private $routes;
    private $request;
    
    public function __construct()
    {
        // dependancies
        $this->frontController = new FrontController();
        $this->backController = new BackController();
        $this->request = new Request();
    }

    // register all routes
    public function register(string $method, ?string $action, string $controller, string $actionController): void
    {
        $route = [
            'method' => $method,
            'action' => $action,
            'controller' => $controller,
            'ac' => $actionController,
        ];
        $this->routes[] = $route;
    }
    
    // Routing entry request
    public function routerRequest(): void
    {
        $method='GET';
        if ($this->request->getPost()) {
            $method='POST';
        };
        if ($this->request->getGet()) {
            $controller = $this->request->getGet()[0];
            $action = $this->request->getGet()[1] ?? null;
        };
        if (!$this->request->getGet()) {
            $controller = $action = null;
        };
        // if controller is not defined, we set it to frontcontroller (default)
        if (!(isset($controller))) {
            $controller = "frontController";
        };

        // if we dont want admin section but we have parameters in url, then we switches parameters and set controller to frontcontroller
        if (($controller <> "admin") && ($controller <> "frontController")) {
            $action = $controller;
            $controller = "frontController";
        };
        // just an alias for admin = backController
        if ($controller === "admin") {
            $controller = "backController";
        };
       
        // checking all registred routes, if one matches we call the controller with its method and pass it $get and/or $post as parameters
        foreach ($this->routes as $route) {
            if ($route['action'] === $action && $route['controller'] === $controller && $route['method'] === $method) {
                if ($controller === "backController") {
                    $isAdmin = true; // temporary, it will be a method that check if user has admin rights
                    if (!$isAdmin) {
                        break;
                    };
                }
                $this->{$route['controller']}->{$route['ac']}($this->request);
                break;
            }
        }
        
        /*
        if (isset($this->get['id'])) {
            if ($this->get['action'] === 'post') {
                if ($this->get['id'] > 0) {
                    if (!isset($this->get['commentPage'])) {
                        $this->get['commentPage'] = 1;
                    }
                    if ($this->get['commentPage'] > 0) {
                        $this->frontController->showSinglePost((int)($this->get['id']), (int)($this->get['commentPage']));
                    }
                }
            }
            if ($this->get['action'] === 'page') {
                if ($this->get['id'] > 0) {
                    $this->frontController->showPostsPage((int)($this->get['id']));
                }
            }
        } elseif (isset($this->get['action'])) {
            if ($this->get['action'] === 'login') {
                $this->frontController->showLoginPage();
            }
            if ($this->get['action'] === 'signin') {
                if ($this->post) {
                    $this->frontController->signinForm($this->post);
                }
                $this->frontController->showSigninPage();
            }
        } elseif (isset($this->get['section'])) {
            if (($this->get['section'] === 'admin') && ($this->get['category'] === 'post') && ($this->get['pagenumber'] > 0)) {
                $this->backController->showPostsManager((int)($this->get['pagenumber'])); //temporary access to backoffice
            }
        } elseif ($this->post) {
            $this->frontController->contactForm($this->post);
        } elseif (!$this->get) {
            $this->frontController->home(); // no paramater, no action -> displaying homepage
        }
        */
    }
}
