<?php
declare(strict_types=1);

namespace  App\Service;

use App\View\View;

class Router
{
    public function __construct()
    {
        // dépendances
        $this->view = new View();
      
        // En attendent de mettre ne place la class App\Service\Http\Request
        $this->get = $_GET;
    }
    
    // Routing entry request
    public function routerRequest()
    {
        $this->home(); // no action : displaying home page
    }
    
    // Affiche page d'accueil
    public function home(): void
    {
        $this->view->render();
    }
}
