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
        $this->twig->addGlobal('basepath', 'http://localhost/OCProject5/public/');
    }

    public function render(string $view, array $params = []): void
    {
        echo $this->twig->render($view, $params);
    }

    public function renderMail(string $view, string $block, array $params = []): string
    {
        $template = $this->twig->load($view);
        $rendered = $template->renderBlock($block, $params);

        return $rendered;
    }
}
