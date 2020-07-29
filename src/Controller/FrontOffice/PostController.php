<?php
declare(strict_types=1);

namespace App\Controller\FrontOffice;

use \App\View\View;
use \App\Model\Repository\PostRepository;
use \App\Model\Manager\PostManager;
use \App\Model\Repository\CommentRepository;
use \App\Model\Manager\CommentManager;
use \App\Service\Http\Request;
use \App\Service\Http\Session;
use \App\Service\Auth;
use \App\Model\Entity\User;

class PostController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;
    private $session;
    private $auth;

    public function __construct()
    {
        $this->renderer = new View();
        $this->postRepo = new PostRepository();
        $this->postManager = new PostManager($this->postRepo);
        $this->commentRepo = new CommentRepository();
        $this->commentManager = new CommentManager($this->commentRepo);
        $this->session = new Session();
        $this->auth = new Auth();
    }

    // Render homepage, by getting the last 4 most recent posts
    public function home(): void
    {
        $listPosts = $this->postManager->getHomepagePosts();
        $this->renderer->render('FrontOffice/HomePage.twig', [
            'listposts' => $listPosts,
            'session' => $this->session->getSession(),
            'user' => $this->auth->user(),
            'token' => $this->auth->generateToken()
            ]);
        $this->session->remove('success')->remove('error')->remove('info');
    }
    
    // Render the single Post view
    public function showSinglePost(Request $request): void
    {
        $postId=$request->getPostId();
        $commentPage=$request->getCommentPage();

        $post = $this->postManager->getSinglePost($postId);
        if ($post === null) {
            header('Location: /posts/1');
            exit();
        }
        $nextId = $this->postManager->getNextPostId($postId);
        $prevId = $this->postManager->getPreviousPostId($postId);
        $totalComments = $this->commentManager->getNumberOfApprovedCommentsFromPost($postId); // total number of Comments
        $pagerArray = $this->postManager->getSinglePostPager($commentPage, $totalComments);
        
        $offset = $pagerArray['offset'];
        $limit = $pagerArray['limit'];
        $totalCommentPages =  $pagerArray['totalCommentPages'];
        $commentPage= $pagerArray['commentPage'];

        $listComments = $this->commentManager->getApprovedComments($postId, (int)$offset, $limit);

        // twig rendering with some parameters
        $this->renderer->render('FrontOffice/SinglePostPage.twig', [
            'post' => $post,
            'postId' => $postId,
            'listcomments' => $listComments,
            'currentPage' => $commentPage,
            'totalPages' => $totalCommentPages,
            'prevId' => $prevId,
            'nextId' => $nextId,
            'session' => $this->session->getSession(),
            'user' => $this->auth->user(),
            'token' => $this->auth->generateToken()
            ]);
        $this->session->remove('success')->remove('error');
    }

    // Render Posts Page
    public function showPostsPage(Request $request): void
    {
        $currentPage=$request->getPostsPage();
       
        $totalItems = $this->postManager->getNumberOfPosts(); // total number of Posts
        $pagerArray = $this->postManager->getPostsPagePager($currentPage, $totalItems);
        $offset = $pagerArray['offset'];
        $limit = $pagerArray['limit'];
        $totalPages = $pagerArray['totalPages'];
        $currentPage = $pagerArray['currentPage'];

        // getting the Posts from DB
        $listPosts = $this->postManager->getPostsPage((int)$offset, $limit);

        $this->renderer->render('FrontOffice/PostsPage.twig', [
            'listposts' => $listPosts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'session' => $this->session->getSession(),
            'user' => $this->auth->user()
            ]);
        $this->session->remove('success')->remove('error');
    }

    // Add comment in DB
    public function addComment(Request $request): void
    {
        // access control, check is user is logged
        if ($this->auth->isLogged() === false) {
            $this->session->setSession(['error' => "Vous devez être authentifié pour pouvoir commenter un article."]);
            header("Location: /account/login#logintitle");
            exit();
        }
       
        $postId=$request->getPostId();
        $user = $this->auth->user();
        $authorId = ($user !== null) ? $user->getUserId() : null;
        $formData = $request->getCommentFormData();
        $comment = $formData['comment'] ?? null;
        $token = $formData['token'] ?? null;

        // access control, check token from form
        if ($this->auth->checkToken($token) === false) {
            $this->session->setSession(['error' => "Erreur de formulaire"]);
            header("Location: /post/$postId/1#comments");
            exit();
        }

        if (($this->commentManager->addCommentToPost($postId, $authorId, $comment)) === true) {
            $this->session->setSession(['success' => "Votre commentaire est enregistré et en attente de validation."]);
        }

        header("Location: /post/$postId/1#comments");
        exit();
    }
}
