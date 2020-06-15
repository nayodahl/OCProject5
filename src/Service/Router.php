<?php
declare(strict_types=1);

namespace  App\Service;

use App\Controller\Frontcontroller;

class Router
{
    public function __construct()
    {
        // dépendances
        $this->controller = new \App\Controller\FrontController();
      
        // En attendent de mettre ne place la class App\Service\Http\Request
        if (isset($_GET)) {
            $this->get = $_GET;
        }
    }
    
    // Routing entry request
    public function routerRequest()
    {
        if (isset($this->get['id'])) {
            if (($this->get['action']) == 'post') {
                if (($this->get['id']) > 0) {
                    if (!isset($this->get['commentPage'])) {
                        $this->get['commentPage'] = 1;
                    }
                    if (($this->get['commentPage']) > 0) {
                        $this->controller->showSinglePost((int)($this->get['id']), (int)($this->get['commentPage']));
                    }
                }
            }
            if (($this->get['action']) == 'page') {
                if (($this->get['id']) > 0) {
                    $this->controller->showPostPage((int)($this->get['id']));
                }
            }
        } elseif (isset($this->get['action'])) {
            if (($this->get['action']) == 'login') {
                $this->controller->showLoginPage();
            }
            if (($this->get['action']) == 'signin') {
                $this->controller->showSigninPage();
            }
        } elseif (!$this->get) {
            $this->controller->home(); // no paramater, no action -> displaying homepage
        }
    }
}
