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
use \App\Model\Entity\User;
use \App\Service\Http\Request;
use \App\Service\Http\Session;
use \App\Service\Auth;

class AdminController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;
    private $userManager;
    private $userRepo;
    private $session;
    private $auth;

    public function __construct()
    {
        $this->renderer = new View();
        $this->postRepo = new PostRepository();
        $this->postManager = new PostManager($this->postRepo);
        $this->commentRepo = new CommentRepository();
        $this->commentManager = new CommentManager($this->commentRepo);
        $this->userRepo = new UserRepository();
        $this->userManager = new UserManager($this->userRepo);
        $this->session = new Session();
        $this->auth = new Auth();
    }

    // Render Posts Manager page (default)
    public function showPostsManager(Request $request): void
    {
        // access control, check is user is logged and admin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isAdmin($userId) === false) {
            header("location: ../../posts/1");
            exit();
        }
        
        $currentPage=$request->getPostsManagerPage();

        $totalItems = $this->postManager->getNumberOfPosts(); // total number of Posts
        $pagerArray = $this->postManager->getPostsPagePager($currentPage, $totalItems);
        $offset = $pagerArray['offset'];
        $limit = $pagerArray['limit'];
        $totalPages = $pagerArray['totalPages'];
        $currentPage = $pagerArray['currentPage'];
        
        // getting the Posts from DB
        $listPosts = $this->postManager->getPostsPage($offset, $limit);

        $this->renderer->render('backoffice/PostsManager.twig', [
            'listposts' => $listPosts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'session' => $this->session->getSession(),
            'user' => $user
            ]);
        $this->session->remove('success')->remove('error');
    }
    
    public function showEditPost(Request $request): void
    {
        // access control, check is user is logged and admin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isAdmin($userId) === false) {
            header("location: ../../posts/1");
            exit();
        }
        
        $postId=$request->getEditPostId();
        $post = $this->postManager->getSinglePost($postId);
        if ($post === null) {
            header('location: ../../admin/posts/1');
            exit();
        }

        $adminUsers = $this->userManager->getAdminUsers();

        // twig rendering with some parameters
        $this->renderer->render('backoffice/EditPost.twig', [
            'post' => $post,
            'postId' => $postId,
            'adminUsers' => $adminUsers,
            'session' => $this->session->getSession(),
            'user' => $user
            ]);
        $this->session->remove('success')->remove('error');
    }

    public function modifyPost(Request $request): void
    {
        // access control, check is user is logged and admin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isAdmin($userId) === false) {
            header("location: ../../posts/1");
            exit();
        }
        
        $postId=$request->getEditPostId();
        $formData = $request->getPostFormData();
        $title = $formData['title'];
        $chapo = $formData['chapo'];
        $authorId = $formData['author'];
        $content = $formData['content'];
                
        $req = $this->postManager->modifyPostContent($postId, $title, $chapo, $authorId, $content);
        
        if ($req === true) {
            $this->session->setSession(['success' => "Article modifié."]);
            header("location: ../../admin/post/$postId#modify");
            exit();
        }
        header("location: ../../admin/post/$postId#modify");
        exit();
    }

    public function delete(Request $request): void
    {
        // access control, check is user is logged and admin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isAdmin($userId) === false) {
            header("location: ../../posts/1");
            exit();
        }

        $postId=$request->getEditPostId();
        if ($this->postManager->deletePost($postId) === true) {
            $this->session->setSession(['success' => "Article supprimé."]);
            header("location: ../../admin/posts/1");
            exit();
        }
        header("location: ../../admin/posts/1");
        exit();
    }

    public function showAddPost(): void
    {
        // access control, check is user is logged and admin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isAdmin($userId) === false) {
            header("location: ../../posts/1");
            exit();
        }

        $adminUsers = $this->userManager->getAdminUsers();
        $this->renderer->render('backoffice/AddPost.twig', [
            'adminUsers' => $adminUsers,
            'session' => $this->session->getSession(),
            'user' => $user
            ]);
        $this->session->remove('success')->remove('error');
    }

    public function addPost(Request $request): void
    {
        // access control, check is user is logged and admin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isAdmin($userId) === false) {
            header("location: ../../posts/1");
            exit();
        }
        
        $formData = $request->getPostFormData();
        $title = $formData['title'];
        $chapo = $formData['chapo'];
        $authorId = $formData['author'];
        $content = $formData['content'];
                
        $newPostId = $this->postManager->createPost($title, $chapo, $authorId, $content);
                
        if (isset($newPostId) && ($newPostId > 0)) {
            $this->session->setSession(['success' => "Article publié."]);
            header("location: ../admin/post/$newPostId");
            exit();
        }
        header("location: ../admin/newpost#add");
        exit();
    }

    public function showCommentsManager(Request $request): void
    {
        // access control, check is user is logged and admin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isAdmin($userId) === false) {
            header("location: ../../posts/1");
            exit();
        }
        
        $commentPage=$request->getCommentManagerPage();
       
        $totalComments = $this->commentManager->getNumberofNotApprovedComments(); // total number of Comments
        $pagerArray = $this->commentManager->getCommentsManagerPager($commentPage, $totalComments);
        $offset = $pagerArray['offset'];
        $limit = $pagerArray['limit'];
        $totalCommentPages = $pagerArray['totalCommentPages'];
        $commentPage = $pagerArray['commentPage'];
        
        $listComments = $this->commentManager->getNotApprovedComments($offset, $limit);

        // twig rendering with some parameters
        $this->renderer->render('backoffice/CommentsManager.twig', [
            'listcomments' => $listComments,
            'currentPage' => $commentPage,
            'totalPages' => $totalCommentPages,
            'session' => $this->session->getSession(),
            'user' => $user
            ]);
        $this->session->remove('success')->remove('error');
    }

    public function approve(Request $request): void
    {
        // access control, check is user is logged and admin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isAdmin($userId) === false) {
            header("location: ../../posts/1");
            exit();
        }
        
        $commentId=$request->getCommentId();
        if ($this->commentManager->approveComment($commentId) === true) {
            $this->session->setSession(['success' => "Commentaire approuvé."]);
            header("location: ../../admin/comments/1");
            exit();
        }
        header("location: ../../admin/comments/1");
        exit();
    }

    public function refuse(Request $request): void
    {
        // access control, check is user is logged and admin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isAdmin($userId) === false) {
            header("location: ../../posts/1");
            exit();
        }

        $commentId=$request->getCommentId();
        if ($this->commentManager->refuseComment($commentId) === true) {
            $this->session->setSession(['success' => "Commentaire supprimé."]);
            header("location: ../../admin/comments/1");
            exit();
        }
        header("location: ../../admin/comments/1");
        exit();
    }
}
