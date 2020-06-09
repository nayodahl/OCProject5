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
        try {
            if (isset($_GET['action'])) {
                if ($_GET['action'] == 'post') {
                    if (isset($_GET['id']) && $_GET['id'] > 0) {
                        $this->controller->post($this->get['id']);
                    } else {
                        throw new Exception('Aucun identifiant de post envoyé');
                    }
                }
            } else {
                $this->controller->home(); // no action : displaying home page
            }
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
}
