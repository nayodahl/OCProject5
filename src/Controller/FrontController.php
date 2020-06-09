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
        $this->renderer = new \App\View\View();
        $this->postRepo = new \App\Model\Repository\PostRepository();
        $this->postManager = new \App\Model\Manager\PostManager($this->postRepo);
    }

    public function home(): void
    {
        $this->renderer->render('frontoffice/homepage.twig');
    }

        
    // Render the single Post view

    public function post($postId): void
    {
        $post = $this->postManager->showSinglePost($postId);
        $this->renderer->render('frontoffice/singlePost.twig', ['post' => $post]);
    }
}
