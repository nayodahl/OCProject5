<?php
declare(strict_types=1);

namespace App\Controller;

use \App\View\View;
use \App\Model\Repository\PostRepository;
use \App\Model\Manager\PostManager;
use \App\Model\Repository\CommentRepository;
use \App\Model\Manager\CommentManager;
use \App\Model\Repository\UserRepository;
use \App\Model\Manager\UserManager;

class BackController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;

    public function __construct()
    {
        $this->renderer = new View();
        $this->postRepo = new PostRepository();
        $this->postManager = new PostManager($this->postRepo);
        $this->commentRepo = new CommentRepository();
        $this->commentManager = new CommentManager($this->commentRepo);
    }

    // Render Posts Manager page (default)
    public function showPostsManager(int $currentPage): void
    {
        $list_posts = $this->postManager->getPosts();

        // Some calculation for the pager for Posts page
        $limit = 4; // number of Posts per page to display
        $offset = ($currentPage - 1) * $limit; // offset, to determine the number of the first Post to display
        $totalItems = count($list_posts); // total number of Posts
        $totalPages = ceil($totalItems / $limit);
        $itemsList = array_splice($list_posts, $offset, $limit);

        $this->renderer->render('backoffice/PostsManager.twig', [
            'listposts' => $itemsList,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
            ]);
    }
}
