<?php

namespace App\Controller;

use App\View\View;
use App\Model\Repository\PostRepository;
use App\Model\Manager\PostManager;

class FrontController
{
    private $renderer;
    private $postRepo;
    private $postManager;

    public function __construct()
    {
        $this->renderer = new View();
        $this->postRepo = new PostRepository();
        $this->postManager = new PostManager($this->postRepo);
    }

    public function home(): void
    {
        $this->renderer->render('frontoffice/homepage.twig');
    }

        
    // Render the single Post view

    public function post($postId): void
    {
        $post = $this->postManager->showOnePost($postId);
        $this->renderer->render('frontoffice/singlePost.twig', ['post' => $post]);
    }
}
