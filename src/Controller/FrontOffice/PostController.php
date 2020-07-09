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

class PostController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;
    private $session;

    public function __construct()
    {
        $this->renderer = new View();
        $this->postRepo = new PostRepository();
        $this->postManager = new PostManager($this->postRepo);
        $this->commentRepo = new CommentRepository();
        $this->commentManager = new CommentManager($this->commentRepo);
        $this->session = new Session();
    }

    // Render homepage, by getting the last 4 most recent posts
    public function home(): void
    {
        $listPosts = $this->postManager->getHomepagePosts();
        $this->renderer->render('frontoffice/HomePage.twig', [
            'listposts' => $listPosts,
            'session' => $this->session->getSession()
            ]);
        $this->session->remove('success');
        $this->session->remove('error');
        $this->session->remove('info');
    }
    
    // Render the single Post view
    public function showSinglePost(Request $request): void
    {
        $postId=$request->getPostId();
        $commentPage=$request->getCommentPage();

        $post = $this->postManager->getSinglePost($postId);
        if ($post->getPostId() === 0) {
            header('location: ../error/404');
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
        $this->renderer->render('frontoffice/SinglePostPage.twig', [
            'post' => $post,
            'postId' => $postId,
            'listcomments' => $listComments,
            'currentPage' => $commentPage,
            'totalPages' => $totalCommentPages,
            'prevId' => $prevId,
            'nextId' => $nextId,
            'session' => $this->session->getSession()
            ]);
        $this->session->remove('success');
        $this->session->remove('error');
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

        $this->renderer->render('frontoffice/PostsPage.twig', [
            'listposts' => $listPosts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
            ]);
    }

    // Add comment in DB
    public function addComment(Request $request): void
    {
        $postId=$request->getPostId();
        $authorId = 8;  //temporary, will need the id from session and checks if loggued
        $comment = $request->getCommentFormData();

        if ($comment === null) {
            $this->session->setSession(['error' => "Une erreur est survenue, Impossible d'ajouter un commentaire vide ou trop long."]);
            header("location: ../post/$postId/1#comments");
            exit();
        }
        
        $req = $this->commentManager->addCommentToPost($postId, $authorId, $comment);

        if ($req === true) {
            $this->session->setSession(['success' => "Votre commentaire est enregistré et en attente de validation."]);
            header("location: ../post/$postId/1#comments");
            exit();
        }

        $this->session->setSession(['error' => "Impossible d'ajouter le commentaire."]);
        header("location: ../post/$postId/1#comments");
        exit();
    }
}
