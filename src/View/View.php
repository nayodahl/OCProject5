<?php
declare(strict_types=1);

namespace App\View;

use \Twig\Environment;
use \Twig\Loader\FilesystemLoader;

class View
{
    private $twig;
    private $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader('../templates');
        $this->twig = new Environment(
            $this->loader,
            [
            'cache' => false,
            'debug' => true,]
        );
    }

    public function render(string $view, array $params = []): void
    {
        echo $this->twig->render($view, $params);
    }
}
