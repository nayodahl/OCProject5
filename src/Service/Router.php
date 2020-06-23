<?php
declare(strict_types=1);

namespace App\Service;

use \App\Controller\FrontOffice\PostController;
use \App\Controller\FrontOffice\AccountController;
use \App\Controller\BackOffice\BackController;
use \App\Service\Http\Request;

class Router
{
    private $postController;
    private $accountController;
    private $backController;
    private $routes;
    private $request;
    
    public function __construct()
    {
        // dependancies
        $this->postController = new PostController();
        $this->accountController = new AccountController();
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
        // if controller is not defined, we set it to default values
        if (!(isset($controller)) && ($method === 'GET')) {$controller = "postController";};
        if (!(isset($controller)) && ($method === 'POST')) {$controller = "accountController";};

        // just aliases
        if ($controller === "admin") {$controller = "backController";};
        if ($controller === "account") {$controller = "accountController";};

        // if we dont want admin section but we have parameters in url, then we switches parameters and set controller to postcontroller
        if (($controller !== "backController") && ($controller !== "postController") && ($controller !== "accountController")) {
            $action = $controller;
            $controller = "postController";
        };
       
        // checking all registred routes, if one matches we call the controller with its method and pass it $get and/or $post as parameters
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['action'] === $action && $route['controller'] === $controller) {
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
    }
}
