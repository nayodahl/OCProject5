<?php
declare(strict_types=1);

namespace App\Controller\FrontOffice;

use \App\View\View;
use \App\Model\Repository\PostRepository;
use \App\Model\Manager\PostManager;
use \App\Model\Repository\CommentRepository;
use \App\Model\Manager\CommentManager;
use \App\Model\Repository\UserRepository;
use \App\Model\Manager\UserManager;
use \App\Service\Http\Request;
use \App\Service\Http\Session;

class AccountController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;
    private $userRepo;
    private $userManager;
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

    // Render Login Page
    public function showLoginPage(): void
    {
        $this->renderer->render('frontoffice/LoginPage.twig', [
            'session' => $this->session->getSession()
        ]);
        $this->session->remove('success');
        $this->session->remove('error');
    }

    // Render Signin Page
    public function showSigninPage(): void
    {
        $this->renderer->render('frontoffice/SigninPage.twig', [
            'session' => $this->session->getSession()
        ]);
        $this->session->remove('success');
        $this->session->remove('error');
    }

    // Contact Form
    public function contactForm(): void
    {
        /*
        Traitement du message, envoi du mail
        */
        $this->session->setSession(['info' => "Votre message a bien été envoyé"]);
        header('location: ');
        exit();
    }

    // Login Form
    public function loginForm(Request $request): void
    {
        $login = $request->getPost()['login'];
        $password = $request->getPost()['password'];
        $user = $this->userManager->login($login, $password);
        
        if ($user) {
            $this->session->setSession([
                'auth' => $user->getUserId(),
                'success' => "Connexion réussie."
            ]);
            header('location: ../');
            exit();
        }
        $this->session->setSession(['error' => "Identifiant ou mot de passe incorrect."]);
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
        $this->session->setSession(['success' => "Votre inscription a bien été enregistrée, vous allez recevoir un mail pour valider votre inscription."]);
        header('location: signin');
        exit();
    }
}
