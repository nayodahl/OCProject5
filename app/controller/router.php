<?php
namespace App\Controller;

use Pub\View\View;
class Router
{
    // Routing entry request
    public function routerRequest()
    {
        try
        {
            $this->home(); // no action : displaying home page
        }
        catch(Exception $e)
        {
            $this->error($e->getMessage());
        }
    }
    
    // Affiche page d'accueil
    private function home()
    {    
        $view = new View();
        $view->generate();
    }
}
