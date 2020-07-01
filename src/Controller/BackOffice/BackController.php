<?php
declare(strict_types=1);

namespace App\Controller\BackOffice;

use \App\View\View;
use \App\Model\Repository\PostRepository;
use \App\Model\Manager\PostManager;
use \App\Model\Repository\CommentRepository;
use \App\Model\Manager\CommentManager;
use \App\Model\Repository\UserRepository;
use \App\Model\Manager\UserManager;
use \App\Service\Http\Request;

class BackController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;
    private $userManager;
    private $userRepo;

    public function __construct()
    {
        $this->renderer = new View();
        $this->postRepo = new PostRepository();
        $this->postManager = new PostManager($this->postRepo);
        $this->commentRepo = new CommentRepository();
        $this->commentManager = new CommentManager($this->commentRepo);
        $this->userRepo = new UserRepository();
        $this->userManager = new UserManager($this->userRepo);
    }

    // Render Posts Manager page (default)
    public function showPostsManager(Request $request): void
    {
        $currentPage=((int)$request->getGet()[2]);

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

        $this->renderer->render('backoffice/PostsManager.twig', [
            'listposts' => $listPosts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
            ]);
    }
    
    public function EditPost(Request $request): void
    {
        $postId=((int)$request->getGet()[2]);
        $post = $this->postManager->getSinglePost($postId);

        // twig rendering with some parameters
        $this->renderer->render('backoffice/EditPost.twig', [
            'post' => $post,
            'postId' => $postId,
            ]);
    }

    public function AddPost(Request $request): void
    {
        $this->renderer->render('backoffice/AddPost.twig');
    }

    public function showCommentsManager(Request $request): void
    {
        $commentPage=((int)$request->getGet()[2]);

        // Some calculation for the pager on Comments
        $limit = 50; // number of Comments per page to display
        $totalComments = $this->commentManager->getNumberofNotApprovedComments(); // total number of Comments
        $totalCommentPages = ceil($totalComments / $limit);
        if ($commentPage > $totalCommentPages) {
            $commentPage=$totalCommentPages; //exit 404 à faire !
        };
        $offset = ($commentPage - 1) * $limit; // offset, to determine the number of the first Comment to display
        
        $listComments = $this->commentManager->getNotApprovedComments((int)$offset, $limit);

        // twig rendering with some parameters
        $this->renderer->render('backoffice/CommentsManager.twig', [
            'listcomments' => $listComments,
            'currentPage' => $commentPage,
            'totalPages' => $totalCommentPages,
            ]);
    }

    public function showUsersManager(Request $request): void
    {
        $userPage=((int)$request->getGet()[2]);

        // Some calculation for the pager on Users
        $limit = 20; // number of Users per page to display
        $totalUsers = $this->userManager->getNumberOfUsers();
        $totalUserPages = ceil($totalUsers / $limit);
        if ($userPage > $totalUserPages) {
            $userPage=$totalUserPages; //exit 404 à faire !
        };
        $offset = ($userPage - 1) * $limit; // offset, to determine the number of the first User to display
        
        // getting the Members from DB
        $listUsers = $this->userManager->getUsersPage((int)$offset, $limit);
        
        $this->renderer->render('backoffice/UsersManager.twig', [
            'listUsers' => $listUsers,
            'currentPage' => $userPage,
            'totalPages' => $totalUserPages
            ]);
    }
}
