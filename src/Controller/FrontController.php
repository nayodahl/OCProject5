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

class FrontController
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
        $this->renderer->render('frontoffice/singlePostPage.twig', [
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

    // Render Login Page
    public function showLoginPage(): void
    {
        $this->renderer->render('frontoffice/loginPage.twig');
    }

    // Render Signin Page
    public function showSigninPage(): void
    {
        $this->renderer->render('frontoffice/signinPage.twig');
    }
}
