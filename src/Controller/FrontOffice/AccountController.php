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

class AccountController
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

    // Render Login Page
    public function showLoginPage(): void
    {
        $this->renderer->render('frontoffice/LoginPage.twig',[
            'session' => $_SESSION 
        ]);
        unset($_SESSION['success']);
        unset($_SESSION['error']);
    } 

    // Render Signin Page
    public function showSigninPage(): void
    {
        $this->renderer->render('frontoffice/SigninPage.twig',[
            'session' => $_SESSION 
        ]);
        unset($_SESSION['success']);
        unset($_SESSION['error']);
    }

    // Contact Form
    public function contactForm(): void
    {
        /*
        Traitement du message, envoi du mail
        */
        $_SESSION['success']="Votre message a bien été envoyé";
        header('location: ');
        exit();
    }

    // Login Form
    public function loginForm(): void
    {
        /*
        Temporaire !!
        Traitement de la connexion
        */
        $_SESSION['success']="Connexion réussie.";
        header('location: login#login');
        exit();
    }

    // Signin Form
    public function signinForm(): void
    {
        /*
        Temporaire !!
        Traitement de l'inscription:
        - create user with status =  not activated
        - send mail with token
        */
        $_SESSION['success']="Votre inscription a bien été enregistrée, vous allez recevoir un mail pour valider votre inscription.";
        header('location: signin');
        exit();
    }
}
