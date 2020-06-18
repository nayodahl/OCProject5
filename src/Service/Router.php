<?php
declare(strict_types=1);

namespace App\Service;

use \App\Controller\FrontController;
use \App\Controller\BackController;

class Router
{
    private $frontController;
    private $backController;
    private $get;
    private $post;
    
    public function __construct()
    {
        // dependancies
        $this->frontController = new FrontController();
        $this->backController = new BackController();
      
        // En attendent de mettre en place la class App\Service\Http\Request
        if (isset($_GET)) {
            $this->get = $_GET;
        }

        if (isset($_POST)) {
            $this->post = $_POST;
        }
    }
    
    // Routing entry request
    public function routerRequest(): void
    {
        if (isset($this->get['id'])) {
            if ($this->get['action'] === 'post') {
                if ($this->get['id'] > 0) {
                    if (!isset($this->get['commentPage'])) {
                        $this->get['commentPage'] = 1;
                    }
                    if ($this->get['commentPage'] > 0) {
                        $this->frontController->showSinglePost((int)($this->get['id']), (int)($this->get['commentPage']));
                    }
                }
            }
            if ($this->get['action'] === 'page') {
                if ($this->get['id'] > 0) {
                    $this->frontController->showPostsPage((int)($this->get['id']));
                }
            }
        } elseif (isset($this->get['action'])) {
            if ($this->get['action'] === 'login') {
                $this->frontController->showLoginPage();
            }
            if ($this->get['action'] === 'signin') {
                if ($this->post) {
                    $this->frontController->signinForm($this->post);
                }
                $this->frontController->showSigninPage();
            }
        } elseif (isset($this->get['section'])) {
            if (($this->get['section'] === 'admin') && ($this->get['category'] === 'post') && ($this->get['pagenumber'] > 0)) {
                $this->backController->showPostsManager((int)($this->get['pagenumber'])); //temporary access to backoffice
            }
        } elseif ($this->post) {
            $this->frontController->contactForm($this->post);
        } elseif (!$this->get) {
            $this->frontController->home(); // no paramater, no action -> displaying homepage
        }
    }
}
