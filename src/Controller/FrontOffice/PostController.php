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
use \App\Service\Http\Request;

class PostController
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
        $listPosts = $this->postManager->getHomepagePosts();
        $this->renderer->render('frontoffice/HomePage.twig', ['listposts' => $listPosts]);
    }
    
    // Render the single Post view
    public function showSinglePost(Request $request): void
    {
        $postId=((int)$request->getGet()[1]);        
        $commentPage=((int)$request->getGet()[2]);        

        $post = $this->postManager->getSinglePost($postId);
        $nextId = $this->postManager->getNextPostId($postId);
        $prevId = $this->postManager->getPreviousPostId($postId);

        // Some calculation for the pager on Comments section
        $limit = 50; // number of Comments per page to display
        $totalComments = $this->commentManager->getNumberOfApprovedCommentsFromPost($postId); // total number of Comments
        $totalCommentPages = ceil($totalComments / $limit);
        if ($commentPage > $totalCommentPages) {
            $commentPage=$totalCommentPages; //exit 404 à faire !
        };
        $offset = ($commentPage - 1) * $limit; // offset, to determine the number of the first Comment to display
        if ($offset < 0) {
            $offset = 0;
        };

        $listComments = $this->commentManager->getApprovedComments($postId, (int)$offset, $limit);

        // twig rendering with some parameters
        $this->renderer->render('frontoffice/SinglePostPage.twig', [
            'post' => $post,
            'postId' => $postId,
            'listcomments' => $listComments,
            'currentPage' => $commentPage,
            'totalPages' => $totalCommentPages,
            'prevId' => $prevId,
            'nextId' => $nextId
            ]);
    }

    // Render Posts Page
    public function showPostsPage(Request $request): void
    {
        $currentPage=((int)$request->getGet()[1]);
       
        // Some calculation for the pager for Posts page
        $limit = 4; // number of Posts per page to display
        $totalItems = $this->postManager->getNumberOfPosts(); // total number of Posts
        $totalPages = ceil($totalItems / $limit);
        if ($currentPage > $totalPages) {
            $currentPage=$totalPages; // exit 404 à faire !!
        };
        $offset = ($currentPage - 1) * $limit; // offset, to determine the number of the first Post to display
        
        // getting the Posts from DB
        $listPosts = $this->postManager->getPostsPage($offset, $limit);
        
        $this->renderer->render('frontoffice/PostsPage.twig', [
            'listposts' => $listPosts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
            ]);
    }
}
