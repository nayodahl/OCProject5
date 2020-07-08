<?php
declare(strict_types=1);

namespace App\Service;

use \App\Controller\FrontOffice\PostController;
use \App\Controller\FrontOffice\AccountController;
use \App\Controller\BackOffice\BackController;
use \App\Controller\ErrorController;
use \App\Service\Http\Request;

class Router
{
    private $postController;
    private $accountController;
    private $backController;
    private $errorController;
    private $routes;
    private $request;
    
    
    public function __construct()
    {
        // dependancies
        $this->request = new Request();
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

        return;
    }

    // check if a route matches the requestUrl, using regex
    public function match(): ?array
    {
        // set Request Url
        $requestUrl = "/";
        if ($this->request->getGet()){
            $requestUrl = "/" . implode('/', $this->request->getGet());
        }
        
        // set Request Method        
        $requestMethod='GET';
        if ($this->request->getPost()) {$requestMethod='POST';};    

        $lastRequestUrlChar = $requestUrl[strlen($requestUrl)-1];       
        
        foreach ($this->routes as $route) {
            
            $method_match = (stripos($route['method'], $requestMethod) !== false);

            // Method did not match, continue to next route.
            if (!$method_match) {
                continue;
            }
 
            if (($position = strpos($route['route'], '[')) === false) {
                // No params in url, do string comparison
                $match = strcmp($requestUrl, $route['route']) === 0;
            } else {
                // Compare longest non-param string with url before moving on to regex
				// Check if last character before param is a slash, because it could be optional if param is optional too
                if (strncmp($requestUrl, $route['route'], $position) !== 0 && ($lastRequestUrlChar === '/' || $route['route'][$position-1] !== '/')) {
                    continue;
                }

                $regex1 = substr($route['route'], 0, strpos($route['route'], '[')) . '[0-9]/?[0-9]?';
                $regex2 = "`^$regex1$`u";
                $match = preg_match($regex2, $requestUrl) === 1;
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
        if ($controller === "backController"){ $this->backController = new BackController(); };
        if ($controller === "accountController"){ $this->accountController = new AccountController(); };
        if ($controller === "postController"){ $this->postController = new PostController(); };
        if ($controller === "errorController"){ $this->errorController = new ErrorController(); };
        
        $this->{$controller}->{$action}($this->request);          
    }
}
