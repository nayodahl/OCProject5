<?php
declare(strict_types=1);

namespace App\Controller;

use \App\View\View;
use \App\Model\Repository\PostRepository;
use \App\Model\Manager\PostManager;
use \App\Model\Repository\CommentRepository;
use \App\Model\Manager\CommentManager;
use \App\Model\Repository\UserRepository;
use \App\Model\Manager\UserManager;
use \App\Service\FormValidator;

class FrontController
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
        $list_posts = $this->postManager->getHomepagePosts();
        $this->renderer->render('frontoffice/homepage.twig', ['listposts' => $list_posts]);
    }
    
    // Render the single Post view
    public function showSinglePost(int $postId, int $commentPage): void
    {
        // get Post content and its Comments
        $post = $this->postManager->getSinglePost($postId);
        $listComments = $this->commentManager->getApprovedComments($postId);

        // getting previous and next postId based on creation date, needed for the pager
        $nextId = $this->postManager->getNextPostId($postId);
        $prevId = $this->postManager->getPreviousPostId($postId);

        // Some calculation for the pager on Comments section
        $limit = 50; // number of Comments per page to display
        $offset = ($commentPage - 1) * $limit; // offset, to determine the number of the first Comment to display
        $totalComments = count($listComments); // total number of Comments
        $totalCommentPages = ceil($totalComments / $limit);
        $itemsList = array_splice($listComments, $offset, $limit);

        // twig rendering with some parameters
        $this->renderer->render('frontoffice/singlePostPage.twig', [
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
    public function showPostsPage(int $currentPage): void
    {
        $list_posts = $this->postManager->getPosts();
        
        // Some calculation for the pager for Posts page
        $limit = 4; // number of Posts per page to display
        $offset = ($currentPage - 1) * $limit; // offset, to determine the number of the first Post to display
        $totalItems = count($list_posts); // total number of Posts
        $totalPages = ceil($totalItems / $limit);
        $itemsList = array_splice($list_posts, $offset, $limit);
        
        $this->renderer->render('frontoffice/postsPage.twig', [
            'listposts' => $itemsList,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
            ]);
    }

    // Render Login Page
    public function showLoginPage(): void
    {
        $this->renderer->render('frontoffice/loginPage.twig');
    }

    // Render Signin Page
    public function showSigninPage(): void
    {
        $this->renderer->render('frontoffice/signinPage.twig');
    }

    // Contact Form
    public function contactForm(array $post): void
    {
        //sanitize input
        $lastname = $this->formValidator->sanitizeString($post['lastname']);
        $firstname =  $this->formValidator->sanitizeString($post['firstname']);
        $email =  $this->formValidator->sanitizeEmail($post['email']);
        $message =  $this->formValidator->sanitizeString($post['message']);

        //validate input
        if (!isset($lastname) || !isset($firstname) || !isset($email) || !isset($message) || !$this->formValidator->isEmail($email)) {
            echo "Tous les champs ne sont pas remplis ou corrects"; // temporaire
        } else {
            /*
            Traitement du message, envoi du mail
            Temporaire
            */
            echo "Votre message a bien été envoyé. <br>";
        }
        $this->home();
    }

    // Signin Form
    public function signinForm(array $post): void
    {
        //sanitize input
        $login = $this->formValidator->sanitizeString($post['login']);
        $email =  $this->formValidator->sanitizeEmail($post['email']);

        //validate input
        /*
            needs a password valid function /
            - check if password has a certain length
            - check if password is complex enought
            needs a login valid, to check is the login is already taken in DB
        */
        $password = $post['password']; // temporaire, need a hash + salt function

        if (!isset($login) || !isset($password) || !isset($email) || !$this->formValidator->isEmail($email)) {
            echo "Tous les champs ne sont pas remplis ou corrects"; // temporaire
            $this->showSigninPage();
        } else {
            /*
                    Temporaire !!
                    Traitement de l'inscription:
                    - create user with status =  not activated
                    - send mail with token

            */
            echo "Votre inscription a bien été enregistrée, vous allez recevoir un mail pour valider votre inscription. <br>";
            $this->showLoginPage();
        }
    }
}
