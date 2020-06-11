<?php

namespace App\Controller;

use App\View\View;
use App\Model\Repository\PostRepository;
use App\Model\Manager\PostManager;
use App\Model\Repository\CommentRepository;
use App\Model\Manager\CommentManager;

class FrontController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;

    public function __construct()
    {
        $this->renderer = new \App\View\View();
        $this->postRepo = new \App\Model\Repository\PostRepository();
        $this->postManager = new \App\Model\Manager\PostManager($this->postRepo);
        $this->commentRepo = new \App\Model\Repository\CommentRepository();
        $this->commentManager = new \App\Model\Manager\CommentManager($this->commentRepo);
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
        $comments = $this->commentManager->getComments($postId);

        $this->renderer->render('frontoffice/singlePost.twig', ['post' => $post, 'listcomments' => $comments]);
    }
}
