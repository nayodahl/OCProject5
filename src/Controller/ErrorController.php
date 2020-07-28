<?php
declare(strict_types=1);

namespace App\Controller;

use \App\View\View;
use \App\Service\Http\Session;
use \App\Service\Auth;

class ErrorController
{
    private $renderer;
    private $session;
    private $auth;

    public function __construct()
    {
        $this->renderer = new View();
        $this->session = new Session();
        $this->auth = new Auth();
    }

    public function show404(): void
    {
        header("HTTP/1.0 404 Not Found");
        $this->renderer->render('FrontOffice/404.twig', [ 
            'session' => $this->session->getSession(),
            'user' => $this->auth->user()
        ]);
    }
}
