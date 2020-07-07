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
use \App\Service\Http\Session;

class BackController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;
    private $userManager;
    private $userRepo;
    private $session;

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
            'totalPages' => $totalPages,
            'session' => $this->session->getSession()
            ]);
        $this->session->remove('success');
        $this->session->remove('error');
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
            'adminUsers' => $adminUsers,
            'session' => $this->session->getSession()
            ]);
        $this->session->remove('success');
        $this->session->remove('error');
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
            $this->session->setSession(['success' => "Article modifié."]);
            header("location: ../../admin/post/$postId#modify");
            exit();
        }
        $this->session->setSession(['error' => "Impossible de modifier l'article."]);
        header("location: ../../admin/post/$postId#modify");
        exit();
    }

    public function delete(Request $request): void
    {
        $postId=((int)$request->getGet()[2]);
        $req = $this->postManager->deletePost($postId);
        
        if ($req === true) {
            $this->session->setSession(['success' => "Article supprimé."]);
            header("location: ../../admin/posts");
            exit();
        }
        $this->session->setSession(['error' => "Impossible de supprimer l'article."]);
        header("location: ../../admin/post/$postId");
        exit();
    }

    public function showAddPost(): void
    {
        $adminUsers = $this->userManager->getAdminUsers();
        $this->renderer->render('backoffice/AddPost.twig', [
            'adminUsers' => $adminUsers,
            'session' => $this->session->getSession()
            ]);
        $this->session->remove('success');
        $this->session->remove('error');
    }

    public function addPost(Request $request): void
    {
        $title = $request->getPost()['title'];
        $chapo = $request->getPost()['chapo'];
        $authorId = ((int)$request->getPost()['author']);
        $content = $request->getPost()['content'];
        
        $newPostId = $this->postManager->createPost($title, $chapo, $authorId, $content);
                
        if (isset($newPostId) && ($newPostId > 0)) {
            $this->session->setSession(['success' => "Article publié."]);
            header("location: ../admin/post/$newPostId");
            exit();
        }
        $this->session->setSession(['error' => "Impossible de publier l'article."]);
        header("location: ../admin/newpost#add");
        exit();
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
            'session' => $this->session->getSession()
            ]);
        $this->session->remove('success');
        $this->session->remove('error');
    }

    public function approve(Request $request): void
    {
        $commentId=((int)$request->getGet()[2]);
        $req = $this->commentManager->approveComment($commentId);

        if ($req === true) {
            $this->session->setSession(['success' => "Commentaire approuvé."]);
            header("location: ../../admin/comments");
            exit();
        }
        $this->session->setSession(['error' => "Impossible d'approuver le commentaire."]);
        header("location: ../../admin/comments");
        exit();
    }

    public function refuse(Request $request): void
    {
        $commentId=((int)$request->getGet()[2]);
        $req = $this->commentManager->refuseComment($commentId);

        if ($req === true) {
            $this->session->setSession(['success' => "Commentaire supprimé."]);
            header("location: ../../admin/comments");
            exit();
        }
        $this->session->setSession(['error' => "Impossible de supprimer le commentaire."]);
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
            'totalPages' => $totalUserPages,
            'session' => $this->session->getSession()
            ]);
        $this->session->remove('success');
        $this->session->remove('error');
    }

    public function promote(Request $request): void
    {
        $userId=((int)$request->getGet()[2]);
        $req = $this->userManager->promoteUser($userId);

        if ($req === true) {
            $this->session->setSession(['success' => "Droits admin donnés à l'utilisateur."]);
            header("location: ../../admin/members");
            exit();
        }
        $this->session->setSession(['error' => "Impossible de donner les droits admin à l'utilisateur."]);
        header("location: ../../admin/members");
        exit();
    }

    public function demote(Request $request): void
    {
        $userId=((int)$request->getGet()[2]);
        $req = $this->userManager->demoteUser($userId);

        if ($req === true) {
            $this->session->setSession(['success' => "Droits admin retirés à l'utilisateur."]);
            header("location: ../../admin/members");
            exit();
        }
        $this->session->setSession(['error' => "Impossible de retirer les droits admin à l'utilisateur. Veuillez vérifier s'il est encore l'auteur d'un ou plusieurs article(s)."]);
        header("location: ../../admin/members");
        exit();
    }
}
