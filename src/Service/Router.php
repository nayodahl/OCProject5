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
                    $this->controller->showSinglePost((int)($this->get['id']));
                }
            }
            if (($this->get['action']) == 'page') {
                if (($this->get['id']) > 0) {
                    $this->controller->showPostPage((int)($this->get['id']));
                }
            }
        } elseif (!$this->get) {
            $this->controller->home(); // no paramater, no action -> displaying homepage
        }
    }
}
