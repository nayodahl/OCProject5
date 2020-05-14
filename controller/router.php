<?php

require_once 'view/view.php';

class Router
{
    // Routing entry request
    public function routerRequest()
    {
        try
        {
            if(isset($_GET['action']))
            {
            }
            else
            {
                $this->home(); // no action : displaying home page
            }
        }
        catch(Exception $e)
        {
            $this->error($e->getMessage());
        }
    }
    
    // Affiche page d'accueil
    private function home()
    {
        $view = new View("Home");
        $view->generate();
    }
}
