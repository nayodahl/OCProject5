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
use \App\Service\FormValidator;
use \App\Service\Http\Request;

class AccountController
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

    // Render Login Page
    public function showLoginPage(): void
    {
        $this->renderer->render('frontoffice/LoginPage.twig');
    }

    // Render Signin Page
    public function showSigninPage(): void
    {
        $this->renderer->render('frontoffice/SigninPage.twig');
    }

    // Contact Form
    public function contactForm(Request $request): void
    {
        //sanitize input
        $lastname = $this->formValidator->sanitizeString($request->getPost()['lastname']);
        $firstname =  $this->formValidator->sanitizeString($request->getPost()['firstname']);
        $email =  $this->formValidator->sanitizeEmail($request->getPost()['email']);
        $message =  $this->formValidator->sanitizeString($request->getPost()['message']);

        //validate input
        if (!isset($lastname) || !isset($firstname) || !isset($email) || !isset($message) || !$this->formValidator->isEmail($email)) {
            echo "Tous les champs ne sont pas remplis ou corrects";
            return; // temporaire
        }
        /*
        Traitement du message, envoi du mail
        Temporaire
        */
        echo "Votre message a bien été envoyé. <br>";
        header('location: ');
    }

    // Signin Form
    public function signinForm(Request $request): void
    {
        //sanitize input
        $login = $this->formValidator->sanitizeString($request->getPost()['login']);
        $email =  $this->formValidator->sanitizeEmail($request->getPost()['email']);

        //validate input
        /*
            needs a password valid function /
            - check if password has a certain length
            - check if password is complex enought
            needs a login valid, to check is the login is already taken in DB
        */
        $password = $request->getPost()['password']; // temporaire, need a hash + salt function

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
            header('location: /account/login');
        }
    }
}
