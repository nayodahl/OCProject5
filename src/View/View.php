<?php
declare(strict_types=1);

namespace App\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('../templates');
        $this->twig = new \Twig\Environment(
            $loader,
            [
            'cache' => false,
            'debug' => true,]
        );
    }

    public function render($view, $params = []): void
    {
        echo $this->twig->render($view, $params);
    }
}
