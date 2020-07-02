<?php
declare(strict_types=1);

namespace App\Controller;

use \App\View\View;

class ErrorController
{
    private $renderer;

    public function __construct()
    {
        $this->renderer = new View();
    }

    public function show404(): void
    {
        $this->renderer->render('frontoffice/404.twig');
    }
}
