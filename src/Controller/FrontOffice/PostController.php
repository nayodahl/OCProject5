<?php
declare(strict_types=1);

namespace App\Controller\FrontOffice;

use \App\View\View;
use \App\Model\Repository\PostRepository;
use \App\Model\Manager\PostManager;
use \App\Model\Repository\CommentRepository;
use \App\Model\Manager\CommentManager;
//use \App\Model\Repository\UserRepository;
//use \App\Model\Manager\UserManager;
use \App\Service\FormValidator;
use \App\Service\Http\Request;

class PostController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;
    private $formValidator;

    public function __construct()
    {
        $this->renderer = new View();
        $this->postRepo = new PostRepository();
        $this->postManager = new PostManager($this->postRepo);
        $this->commentRepo = new CommentRepository();
        $this->commentManager = new CommentManager($this->commentRepo);
        $this->formValidator = new FormValidator();
    }

    // Render homepage, by getting the last 4 most recent posts
    public function home(): void
    {
        $listPosts = $this->postManager->getHomepagePosts();
        $this->renderer->render('frontoffice/HomePage.twig', ['listposts' => $listPosts]);
    }
    
    // Render the single Post view
    public function showSinglePost(Request $request): void
    {
        // validating $get inputs
        $postId = 1;
        if (isset($request->getGet()[1]) &&  ($request->getGet()[1] > 0)) {
            $postId=((int)$request->getGet()[1]);
        };
        
        $commentPage=1;
        if (isset($request->getGet()[2]) &&  ($request->getGet()[2] > 0)) {
            $commentPage=((int)$request->getGet()[2]);
        };
        
        // get Post content and its Comments
        $post = $this->postManager->getSinglePost($postId);
        $listComments = $this->commentManager->getApprovedComments($postId);

        // getting previous and next postId based on creation date, needed for the pager
        $nextId = $this->postManager->getNextPostId($postId);
        $prevId = $this->postManager->getPreviousPostId($postId);

        // Some calculation for the pager on Comments section
        $limit = 50; // number of Comments per page to display
        $totalComments = count($listComments); // total number of Comments
        $totalCommentPages = ceil($totalComments / $limit);
        if ($commentPage > $totalCommentPages) {
            $commentPage=$totalCommentPages;
        };
        $offset = ($commentPage - 1) * $limit; // offset, to determine the number of the first Comment to display
        $itemsList = array_splice($listComments, (int)$offset, $limit);

        // twig rendering with some parameters
        $this->renderer->render('frontoffice/SinglePostPage.twig', [
            'post' => $post,
            'postId' => $postId,
            'listcomments' => $itemsList,
            'currentPage' => $commentPage,
            'totalPages' => $totalCommentPages,
            'prevId' => $prevId,
            'nextId' => $nextId
            ]);
    }

    // Render Posts Page
    public function showPostsPage(Request $request): void
    {
        $currentPage=1;
        // validating $_GET
        if (isset($request->getGet()[1]) && ($request->getGet()[1] > 0)) {
            $currentPage=((int)$request->getGet()[1]);
        };

        $listPosts = $this->postManager->getPosts(); // à corriger
        
        // Some calculation for the pager for Posts page
        $limit = 4; // number of Posts per page to display
        $totalItems = count($listPosts); // total number of Posts
        $totalPages = ceil($totalItems / $limit);
        if ($currentPage > $totalPages) {
            $currentPage=$totalPages;
        };
        $offset = ($currentPage - 1) * $limit; // offset, to determine the number of the first Post to display
        $itemsList = array_splice($listPosts, (int)$offset, $limit);
        
        $this->renderer->render('frontoffice/PostsPage.twig', [
            'listposts' => $itemsList,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
            ]);
    }
}