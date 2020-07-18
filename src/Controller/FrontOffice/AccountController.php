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
use \App\Service\Auth;

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

    // Render Login Page
    public function showLoginPage(): void
    {
        // access control, check is user is not logged
        if ($this->auth->user() !== null) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e)"]);
            header('location: ../posts/1');
            exit();
        }

        $this->renderer->render('frontoffice/LoginPage.twig', [
            'session' => $this->session->getSession()
        ]);
        $this->session->remove('success');
        $this->session->remove('error');
    }

    // Render Signin Page
    public function showSigninPage(): void
    {
        // access control, check is user is not logged
        if ($this->auth->user() !== null) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e) et avez donc un compte"]);
            header('location: ../posts/1');
            exit();
        }
        
        $this->renderer->render('frontoffice/SigninPage.twig', [
            'session' => $this->session->getSession()
        ]);
        $this->session->remove('success');
        $this->session->remove('error');

        // $objet->settoto1()->settoto2()->settoto(); //
    }

    // Contact Form
    public function contactForm(Request $request): void
    {
        $formData = $request->getContactFormData();
        $lastname = $formData['lastname'];
        $firstname = $formData['firstname'];
        $email = $formData['email'];
        $message = $formData['message'];

        if ($lastname === '' || (mb_strlen($lastname) > Request::MAX_STRING_LENGTH) ||
            $firstname === '' || (mb_strlen($firstname) > Request::MAX_STRING_LENGTH) ||
            $email === '' || (mb_strlen($email) > Request::MAX_STRING_LENGTH) || (filter_var($email, FILTER_VALIDATE_EMAIL) === false) ||
            $message === '' || (mb_strlen($message) > Request::MAX_TEXTAREA_LENGTH)
            ) {
            $this->session->setSession(['error' => "tous les champs ne sont pas remplis ou corrects."]);
            header('location: ');
            exit();
        }
        
        // rendering html content of mail with twig
        $subject = $this->renderer->renderMail('frontoffice/contactMail.twig', 'subject');
        $message = $this->renderer->renderMail('frontoffice/contactMail.twig', 'message', [ 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'message' => $message ]);

        $headers = [
            'From' => 'contact@blog.nayo.cloud',
            'X-Mailer' => 'PHP/' . phpversion(),
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=utf-8'
        ];
        
        // send mail
        if (mail('contact@blog.nayo.cloud', $subject, $message, $headers) === false) {
            $this->session->setSession(['error' => "Erreur lors de l'envoi du message"]);
            header('location: ');
            exit();
        };

        $this->session->setSession(['info' => "Votre message a bien été envoyé"]);
        header('location: ');
        exit();
    }

    // Login Form
    public function loginForm(Request $request): void
    {
        // access control, check is user is not logged
        if ($this->auth->user() !== null) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e)"]);
            header('location: ../posts/1');
            exit();
        }
        
        $formData = $request->getLoginFormData();
        $login = $formData['login'];
        $password = $formData['password'];
        
        $user = $this->userManager->login($login, $password);
        
        if ($user !== null) {
            header('location: ../');
            exit();
        }
        header('location: login#login');
        exit();
    }

    // Logout
    public function logout(): void
    {
        $this->session->destroy();
        header('location: ../');
        exit();
    }

    // Signin Form
    public function signinForm(Request $request): void
    {
        // access control, check is user is not logged
        if ($this->auth->user() !== null) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e) et avez donc un compte"]);
            header('location: ../posts/1');
            exit();
        }
        
        $formData = $request->getSigninFormData();
        $login = $formData['login'];
        $password = $formData['password'];
        $email = $formData['email'];

        $req = $this->userManager->signin($login, $password, $email);
        if ($req !== null) {
            $dest = $req['dest'];
            $token = $req['token'];
            $server = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'www.blog.nayo.cloud';
            
            // rendering html content of mail with twig
            $subject = $this->renderer->renderMail('frontoffice/signinMail.twig', 'subject');
            $message = $this->renderer->renderMail('frontoffice/signinMail.twig', 'message', [ 'token' => $token, 'server' => $server ]);

            $headers = [
                'From' => 'contact@blog.nayo.cloud',
                'X-Mailer' => 'PHP/' . phpversion(),
                'MIME-Version' => '1.0',
                'Content-type' => 'text/html; charset=utf-8'
            ];
            
            // send mail
            if (mail($dest, $subject, $message, $headers) === true) {
                $this->session->setSession(['success' => "Votre inscription a bien été enregistrée, vous allez recevoir un mail pour valider votre inscription."]);
                header('location: login#login');
                exit();
            };
        }

        header('location: signin#signin');
        exit();
    }

    //activate user
    public function activate(Request $request): void
    {
        // access control, check is user is not logged
        if ($this->auth->user() !== null) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e) et avez donc un compte"]);
            header('location: ../posts/1');
            exit();
        }

        $req = $this->userManager->activateUser($request->getToken());

        if ($req === true) {
            $this->session->setSession(['success' => "Votre inscription est définitivement validée, vous pouvez vous connecter."]);
            header('location: ../login#login');
            exit();
        }
       
        $this->session->setSession(['error' => "Impossible de confirmer votre compte, erreur de token"]);
        header('location: ../signin#signin');
        exit();
    }
}
