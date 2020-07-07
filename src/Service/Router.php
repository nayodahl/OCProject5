<?php
declare(strict_types=1);

namespace App\Service;

use \App\Controller\FrontOffice\PostController;
use \App\Controller\FrontOffice\AccountController;
use \App\Controller\BackOffice\BackController;
use \App\Controller\ErrorController;
use \App\Service\Http\Request;
use \App\Service\Http\RequestValidator;

class Router
{
    private $postController;
    private $accountController;
    private $backController;
    private $errorController;
    private $routes;
    private $request;
    private $requestValidator;
    protected $basePath = '/OCProjet5/public/';

        /**
     * @var array Array of default match types (regex helpers)
     */
    protected $matchTypes = [
        'i'  => '[0-9]++',
        'a'  => '[0-9A-Za-z]++',
        'h'  => '[0-9A-Fa-f]++',
        '*'  => '.+?',
        '**' => '.++',
        ''   => '[^/\.]++'
    ];
    
    public function __construct()
    {
        // dependancies
        $this->postController = new PostController();
        $this->accountController = new AccountController();
        $this->backController = new BackController();
        $this->errorController = new ErrorController();
        $this->request = new Request();
        $this->requestValidator = new RequestValidator();
    }

    // register all routes
    public function register(string $method, ?string $route, string $controller, string $action): void
    {
        $this->routes[] = [$method, $route, $controller, $action];
        return;
    }

    public function match($requestUrl = null, $requestMethod = null)
    {

        $params = [];

        // set Request Url if it isn't passed as parameter
        if ($requestUrl === null) {
            $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        }

        // strip base path from request url
        $requestUrl = substr($requestUrl, strlen($this->basePath));

        // Strip query string (?a=b) from Request Url
        if (($strpos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $strpos);
        }

        $lastRequestUrlChar = $requestUrl[strlen($requestUrl)-1];

        // set Request Method if it isn't passed as a parameter
        if ($requestMethod === null) {
            $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        }

        foreach ($this->routes as $handler) {
            list($methods, $route, $controller, $action) = $handler;

            $method_match = (stripos($methods, $requestMethod) !== false);

            // Method did not match, continue to next route.
            if (!$method_match) {
                continue;
            }
 
            if (($position = strpos($route, '[')) === false) {
                // No params in url, do string comparison
                $match = strcmp($requestUrl, $route) === 0;
            } else {
                // Compare longest non-param string with url before moving on to regex
				// Check if last character before param is a slash, because it could be optional if param is optional too (see https://github.com/dannyvankooten/AltoRouter/issues/241)
                if (strncmp($requestUrl, $route, $position) !== 0 && ($lastRequestUrlChar === '/' || $route[$position-1] !== '/')) {
                    continue;
                }

                $regex = $this->compileRoute($route);
                $match = preg_match($regex, $requestUrl, $params) === 1;
            }

            if ($match) {

                return [
                    'controller' => $controller,
                    'action' => $action
                ];
            }
        }

        return false;
    }
    
    // Routing entry request
    public function routerRequest($controller, $action): void
    {
        $cname = "\App\Controller\\" . $controller;
        $this->{$controller}->{$action}($this->request);          
    }

        /**
     * Compile the regex for a given route (EXPENSIVE)
     * @param $route
     * @return string
     */
    protected function compileRoute($route)
    {
        if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {
            $matchTypes = $this->matchTypes;
            foreach ($matches as $match) {
                list($block, $pre, $type, $param, $optional) = $match;

                if (isset($matchTypes[$type])) {
                    $type = $matchTypes[$type];
                }
                if ($pre === '.') {
                    $pre = '\.';
                }

                $optional = $optional !== '' ? '?' : null;

                //Older versions of PCRE require the 'P' in (?P<named>)
                $pattern = '(?:'
                        . ($pre !== '' ? $pre : null)
                        . '('
                        . ($param !== '' ? "?P<$param>" : null)
                        . $type
                        . ')'
                        . $optional
                        . ')'
                        . $optional;

                $route = str_replace($block, $pattern, $route);
            }
        }
        return "`^$route$`u";
    }
}
