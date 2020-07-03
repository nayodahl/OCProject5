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

        $totalItems = $this->postManager->getNumberOfPosts(); // total number of Posts
        $pagerArray = $this->postManager->getPostsPagePager($currentPage, $totalItems);
        $offset = $pagerArray[0];
        $limit = $pagerArray[1];
        $totalPages = $pagerArray[2];
        $currentPage = $pagerArray[3];
        
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

    public function AddPost(): void
    {
        $this->renderer->render('backoffice/AddPost.twig');
    }

    public function showCommentsManager(Request $request): void
    {
        $commentPage=((int)$request->getGet()[2]);
       
        $totalComments = $this->commentManager->getNumberofNotApprovedComments(); // total number of Comments
        $pagerArray = $this->commentManager->getCommentsManagerPager($commentPage, $totalComments);
        $offset = $pagerArray[0];
        $limit = $pagerArray[1];
        $totalCommentPages = $pagerArray[2];
        $commentPage = $pagerArray[3];
        
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

        $totalUsers = $this->userManager->getNumberOfUsers();
        $pagerArray = $this->userManager->getUsersManagerPager($userPage, $totalUsers);
        $offset = $pagerArray[0];
        $limit = $pagerArray[1];
        $totalUserPages = $pagerArray[2];
        $userPage = $pagerArray[3];
        
        // getting the Members from DB
        $listUsers = $this->userManager->getUsersPage((int)$offset, $limit);
        
        $this->renderer->render('backoffice/UsersManager.twig', [
            'listUsers' => $listUsers,
            'currentPage' => $userPage,
            'totalPages' => $totalUserPages
            ]);
    }
}
