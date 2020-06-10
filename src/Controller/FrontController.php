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

    // render homepage, by getting the last 4 most recent posts
    public function home(): void
    {
        $list_posts = $this->postManager->getLastPosts(4);
        $this->renderer->render('frontoffice/homepage.twig', ['listposts' => $list_posts]);
    }
    
    // Render the single Post view

    public function showSinglePost($postId): void
    {
        $post = $this->postManager->getSinglePost($postId);
        $this->renderer->render('frontoffice/singlePost.twig', ['post' => $post]);
    }
}
