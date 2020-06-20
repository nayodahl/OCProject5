<?php
declare(strict_types=1);

namespace App\Service;

use \App\Controller\FrontController;
use \App\Controller\BackController;

class Router
{
    private $frontController;
    private $backController;
    private $router;
    private $get;
    private $post;
    private $routes;
    
    public function __construct()
    {
        // dependancies
        $this->frontController = new FrontController();
        $this->backController = new BackController();
      
        // En attendent de mettre en place la class App\Service\Http\Request
        $this->get = null;
        $this->post = null;
        if (isset($_GET['url'])) {
            $this->get = $_GET;
            $this->get['url'] = explode('/', $this->get['url']);
        }        

        if (isset($_POST)) {
            $this->post = $_POST;
        }

    }

    public function register(string $method, string $action, string $controller, string $actionController): void {
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
        if ($this->get){ $method='GET'; };
        $controller = $this->get['url'][0] ?? null;
        $action = $this->get['url'][1] ?? null;
        $param = $this->get['url'][2] ?? 1;
        
        // if controller is not defined, we set it to frontcontroller (default)
        if  (!(isset($controller))) {
            $controller = "frontController";
        };

        // if we dont want admin section but we have parameters in url, then we switches parameters and set controller to frontcontroller
        if (( $controller != "admin") || ( $controller != "frontController" )){
            $param = $action;
            $action = $controller;
            $controller = "frontController";
        };
       
        // checking all registred routes, if one matches
        foreach($this->routes as $route) {
            if ($route['action'] === $action && $route['controller'] === $controller) {
                $this->{$route['controller']}->{$route['ac']}($this->get['url']);
                break;
            }
            // if no action, showing homepage
            if ( $action === $controller) {
                $this->{$route['controller']}->{$route['ac']}();
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
