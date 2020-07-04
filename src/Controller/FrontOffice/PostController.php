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
        $this->renderer->render('frontoffice/HomePage.twig', [
            'listposts' => $listPosts,
            'session' => $_SESSION
            ]);
        unset($_SESSION['success']);
        unset($_SESSION['error']);
    }
    
    // Render the single Post view
    public function showSinglePost(Request $request): void
    {
        $postId=((int)$request->getGet()[1]);
        $commentPage=((int)$request->getGet()[2]);

        $post = $this->postManager->getSinglePost($postId);
        if ($post->getPostId() === 0) {
            header('location: ../error/404');
            exit();
        }
        $nextId = $this->postManager->getNextPostId($postId);
        $prevId = $this->postManager->getPreviousPostId($postId);
        $totalComments = $this->commentManager->getNumberOfApprovedCommentsFromPost($postId); // total number of Comments
        $pagerArray = $this->postManager->getSinglePostPager($commentPage, $totalComments);
        
        $offset = $pagerArray[0];
        $limit = $pagerArray[1];
        $totalCommentPages =  $pagerArray[2];
        $commentPage= $pagerArray[3];

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
       
        $totalItems = $this->postManager->getNumberOfPosts(); // total number of Posts
        $pagerArray = $this->postManager->getPostsPagePager($currentPage, $totalItems);
        $offset = $pagerArray[0];
        $limit = $pagerArray[1];
        $totalPages = $pagerArray[2];
        $currentPage = $pagerArray[3];

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
        $postId=(int)$request->getGet()[1];
        $authorId = 8;  //temporary, will need the id from session and checks if loggued
        $comment = $request->getPost()['comment'];
        
        $req = $this->commentManager->addCommentToPost($postId, $authorId, $comment);

        if ($req === true) {
            echo "Votre commentaire va être soumis à validation.";
            header("location: ../post/$postId#comments");
            exit();
        }

        echo "Impossible d'ajouter le commentaire <br>";
        header("location: ../post/$postId#comments");
        exit();
    }
}
