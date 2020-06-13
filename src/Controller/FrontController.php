<?php

namespace App\Controller;

use App\View\View;
use App\Model\Repository\PostRepository;
use App\Model\Manager\PostManager;
use App\Model\Repository\CommentRepository;
use App\Model\Manager\CommentManager;
use App\Model\Repository\UserRepository;
use App\Model\Manager\UserManager;

class FrontController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;
    private $userRepo;
    private $userManager;

    public function __construct()
    {
        $this->renderer = new \App\View\View();
        $this->postRepo = new \App\Model\Repository\PostRepository();
        $this->postManager = new \App\Model\Manager\PostManager($this->postRepo);
        $this->commentRepo = new \App\Model\Repository\CommentRepository();
        $this->commentManager = new \App\Model\Manager\CommentManager($this->commentRepo);
        $this->userRepo = new \App\Model\Repository\UserRepository();
        $this->userManager = new \App\Model\Manager\UserManager($this->userRepo);
    }

    // Render homepage, by getting the last 4 most recent posts
    public function home(): void
    {
        $list_posts = $this->postManager->getHomepagePosts();
        $this->renderer->render('frontoffice/homepage.twig', ['listposts' => $list_posts]);
    }
    
    // Render the single Post view
    public function showSinglePost(int $postId, int $commentPage): void
    {
        // get Post content and its Comments
        $post = $this->postManager->getSinglePost($postId);
        $listComments = $this->commentManager->getApprovedComments($postId);

        // getting number of Posts, needed for the pager on the Post section
        $totalPosts = $this->postManager->getNumberOfPosts();

        // Some calculation for the pager on Comments section
        $limit = 4; // number of Comments per page to display
        $offset = ($commentPage - 1) * $limit; // offset, to determine the number of the first Comment to display
        $totalComments = count($listComments); // total number of Comments
        $totalCommentPages = ceil($totalComments / $limit);
        $itemsList = array_splice($listComments, $offset, $limit);

        // twig rendering with some parameters
        $this->renderer->render('frontoffice/singlePost.twig', [
            'post' => $post,
            'postId' => $postId,
            'listcomments' => $itemsList,
            'currentPage' => $commentPage,
            'totalPages' => $totalCommentPages,
            'totalPosts' => $totalPosts,
            ]);
    }

    // Render Posts Page
    public function showPostPage(int $currentPage): void
    {
        $list_posts = $this->postManager->getPosts();
        
        // Some calculation for the pager for Posts page
        $limit = 4; // number of Posts per page to display
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
