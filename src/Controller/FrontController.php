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

    // Render homepage, by getting the last 4 most recent posts
    public function home(): void
    {
        $list_posts = $this->postManager->getHomepagePosts();
        $this->renderer->render('frontoffice/homepage.twig', ['listposts' => $list_posts]);
    }
    
    // Render the single Post view

    public function showSinglePost(int $postId): void
    {
        $post = $this->postManager->getSinglePost($postId);
        $comments = $this->commentManager->getApprovedComments($postId);

        $this->renderer->render('frontoffice/singlePost.twig', ['post' => $post, 'listcomments' => $comments]);
    }

    // Render Posts Page
    public function showPostPage(int $currentPage): void
    {
        $list_posts = $this->postManager->getPosts();
        
        $limit = 2; // number of Posts per page to display
        $offset = ($currentPage - 1) * $limit; // offset, to determine the number of the first Post to display
        $totalItems = count($list_posts); // total number of Posts
        $totalPages = ceil($totalItems / $limit);
        $itemsList = array_splice($list_posts, $offset, $limit);
        
        $this->renderer->render('frontoffice/postsPage.twig', [
            'listposts' => $itemsList,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
            ]);
    }
}
