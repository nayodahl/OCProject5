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
    
    public function showEditPost(Request $request): void
    {
        $postId=((int)$request->getGet()[2]);
        $post = $this->postManager->getSinglePost($postId);
        $adminUsers = $this->userManager->getAdminUsers();

        // twig rendering with some parameters
        $this->renderer->render('backoffice/EditPost.twig', [
            'post' => $post,
            'postId' => $postId,
            'adminUsers' => $adminUsers
            ]);
    }

    public function modifyPost(Request $request): void
    {
        $postId=((int)$request->getGet()[2]);
        $title = $request->getPost()['title'];
        $chapo = $request->getPost()['chapo'];
        $authorId = ((int)$request->getPost()['author']);
        $content = $request->getPost()['content'];
        
        $req = $this->postManager->modifyPostContent($postId, $title, $chapo, $authorId, $content);
        
        if ($req === true) {
            echo "Article modifié.";
            header("location: ../../admin/post/$postId");
            exit();
        }

        echo "Impossible de modifier l'article <br>";
        header("location: ../../admin/post/$postId");
        exit();
    }

    public function showAddPost(): void
    {
        $adminUsers = $this->userManager->getAdminUsers();
        $this->renderer->render('backoffice/AddPost.twig', ['adminUsers' => $adminUsers]);
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

    public function approve(Request $request): void
    {
        $commentId=((int)$request->getGet()[2]);
        $req = $this->commentManager->approveComment($commentId);

        if ($req === true) {
            echo "Commentaire approuvé.";
            header("location: ../../admin/comments");
            exit();
        }

        echo "Impossible d'approuver le commentaire <br>";
        header("location: ../../admin/comments");
        exit();
    }

    public function refuse(Request $request): void
    {
        $commentId=((int)$request->getGet()[2]);
        $req = $this->commentManager->refuseComment($commentId);

        if ($req === true) {
            echo "Commentaire supprimé.";
            header("location: ../../admin/comments");
            exit();
        }

        echo "Impossible de supprimer le commentaire <br>";
        header("location: ../../admin/comments");
        exit();
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
