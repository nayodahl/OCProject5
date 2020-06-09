<?php
declare(strict_types=1);

namespace  App\Service;

use App\Controller\Frontcontroller;

class Router
{
    public function __construct()
    {
        // dépendances
        $this->controller = new FrontController();
      
        // En attendent de mettre ne place la class App\Service\Http\Request
        $this->get = $_GET;
    }
    
    // Routing entry request
    public function routerRequest()
    {
        if (isset($this->get['id'])) {
            if (($this->get['action']) == 'post') {
                if (isset($this->get['id']) && ($this->get['id']) > 0) {
                    $this->controller->post($this->get['id']);
                } else {
                    throw new Exception('Aucun identifiant de post envoyé');
                }
            }
        } 
        else {
            $this->controller->home(); // no action : displaying home page
        }
    }
}
