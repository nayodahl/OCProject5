<?php
declare(strict_types=1);

namespace App\Controller\FrontOffice;

use \App\View\View;
use \App\Model\Manager\PostManager;
use \App\Model\Manager\CommentManager;
use \App\Model\Manager\UserManager;
use \App\Service\Http\Request;
use \App\Service\Http\Session;
use \App\Service\Auth;
use \App\Service\Config;

class AccountController
{
    private $postManager;
    private $commentManager;
    private $userManager;
    private $renderer;
    private $session;
    private $auth;
    private $config;

    public function __construct(PostManager $postManager, CommentManager $commentManager, UserManager $userManager, View $renderer, Session $session, Auth $auth, Config $config)
    {
        $this->postManager = $postManager;
        $this->commentManager = $commentManager;
        $this->userManager = $userManager;
        $this->renderer = $renderer;
        $this->session = $session;
        $this->auth = $auth;
        $this->config = $config;
    }

    // Render Login Page
    public function showLoginPage(): void
    {
        // access control, check is user is not logged
        if ($this->auth->isLogged() === true) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e)"]);
            header('Location: /posts/1');
            exit();
        }

        $this->renderer->render('FrontOffice/LoginPage.twig', [
            'session' => $this->session->getSession(),
            'token' => $this->auth->generateToken()
        ]);
        $this->session->remove('success')->remove('error')->remove('info');
    }

    // Render Signin Page
    public function showSigninPage(): void
    {
        // access control, check is user is not logged
        if ($this->auth->isLogged() === true) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e) et avez donc un compte"]);
            header('Location: /posts/1');
            exit();
        }
        
        $this->renderer->render('FrontOffice/SigninPage.twig', [
            'session' => $this->session->getSession(),
            'token' => $this->auth->generateToken()
        ]);
        $this->session->remove('success')->remove('error')->remove('info');
    }

    // Contact Form
    public function contactForm(Request $request): void
    {
        $formData = $request->getContactFormData();
        $lastname = $formData['lastname'] ?? null;
        $firstname = $formData['firstname'] ?? null;
        $email = $formData['email'] ?? null;
        $message = $formData['message'] ?? null;
        $token = $formData['token'] ?? null;

        // access control, check token from form
        if ($this->auth->checkToken($token) === false) {
            $this->session->setSession(['error' => "Erreur de formulaire"]);
            header("Location: /#contact");
            exit();
        }

        // input control
        $badLastName = ($lastname === null) || (mb_strlen($lastname) > Request::MAX_STRING_LENGTH) || (mb_strlen($lastname) < Request::MIN_STRING_LENGTH);
        $badFirstName = ($firstname === null) || (mb_strlen($firstname) > Request::MAX_STRING_LENGTH) || (mb_strlen($lastname) < Request::MIN_STRING_LENGTH);
        $badEmail = ($email === null) || (mb_strlen($email) > Request::MAX_STRING_LENGTH) || (filter_var($email, FILTER_VALIDATE_EMAIL) === false);
        $badMessage = ($message === null) || (mb_strlen($message) > Request::MAX_TEXTAREA_LENGTH) || (mb_strlen($lastname) < Request::MIN_TEXTAREA_LENGTH);
        if ($badLastName || $badFirstName || $badEmail || $badMessage) {
            $this->session->setSession(['error' => "tous les champs ne sont pas remplis ou corrects."]);
            header('Location: /#contact');
            exit();
        }

        //create a boundary for the email.
        $boundary = uniqid('np');

        $headers = [
            'From' => $this->config->contactMail,
            'X-Mailer' => 'PHP/' . phpversion(),
            'MIME-Version' => '1.0',
            'Content-type' => "multipart/alternative;boundary=\"" . $boundary . "\""
        ];
        
        // rendering html content of mail with twig
        $subject = $this->renderer->renderMail('FrontOffice/ContactMail.twig', 'subject');
        $message = $this->renderer->renderMail('FrontOffice/ContactMail.twig', 'message', [ 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'message' => $message, 'boundary' => $boundary ]);
        
        // send mail, change first parameter to your own choosen contact mail if needed
        if (mail($this->config->contactMail, $subject, $message, $headers) === false) {
            $this->session->setSession(['error' => "Erreur lors de l'envoi du message"]);
            header('Location: /#contact');
            exit();
        };

        $this->session->setSession(['info' => "Votre message a bien été envoyé"]);
        header('Location: /#contact');
        exit();
    }

    // Login Form
    public function loginForm(Request $request): void
    {
        // access control, check is user is not logged
        if ($this->auth->isLogged() === true) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e)"]);
            header('Location: /posts/1');
            exit();
        }
        
        $formData = $request->getLoginFormData();
        $login = $formData['login'] ?? null;
        $password = $formData['password'] ?? null;
        $token = $formData['token'] ?? null;

        // access control, check token from form
        if ($this->auth->checkToken($token) === false) {
            $this->session->setSession(['error' => "Erreur de formulaire"]);
            header("Location: /account/login#logintitle");
            exit();
        }
        
        $user = $this->userManager->login($login, $password);
        
        if ($user !== null) {
            header('Location: /');
            exit();
        }
        header('Location: /account/login#logintitle');
        exit();
    }

    // Logout
    public function logout(): void
    {
        $this->session->destroy();
        header('Location: /');
        exit();
    }

    // Signin Form
    public function signinForm(Request $request): void
    {
        // access control, check is user is not logged
        if ($this->auth->isLogged() === true) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e) et avez donc un compte"]);
            header('Location: /posts/1');
            exit();
        }
        
        $formData = $request->getSigninFormData();
        $login = $formData['login'] ?? null;
        $password = $formData['password'] ?? null;
        $confirm = $formData['confirm'] ?? null;
        $email = $formData['email'] ?? null;
        $token = $formData['token'] ?? null;

        // access control, check token from form
        if ($this->auth->checkToken($token) === false) {
            $this->session->setSession(['error' => "Erreur de formulaire"]);
            header("Location: /account/login#logintitle");
            exit();
        }

        $req = $this->userManager->signin($login, $password, $confirm, $email);
        if ($req !== null) {
            $dest = $req['dest'];
            $token = $req['token'];
            
            //create a boundary for the email.
            $boundary = uniqid('np');

            $headers = [
                'From' => $this->config->contactMail,
                'X-Mailer' => 'PHP/' . phpversion(),
                'MIME-Version' => '1.0',
                'Content-type' => "multipart/alternative;boundary=\"" . $boundary . "\""
            ];

            // rendering html content of mail with twig
            $subject = $this->renderer->renderMail('FrontOffice/SigninMail.twig', 'subject');
            $message = $this->renderer->renderMail('FrontOffice/SigninMail.twig', 'message', [ 'token' => $token, 'boundary' => $boundary, 'basepath' => $this->config->serverUrl]);

            // send mail
            if (mail($dest, $subject, $message, $headers) === true) {
                $this->session->setSession(['success' => "Votre inscription a bien été enregistrée, vous allez recevoir un mail pour valider votre inscription."]);
                header('Location: /account/login#logintitle');
                exit();
            };
        }

        header('Location: /account/signin#signin');
        exit();
    }

    //activate user
    public function activate(Request $request): void
    {
        // access control, check is user is not logged
        if ($this->auth->isLogged() === true) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e) et avez donc un compte"]);
            header('Location: /posts/1');
            exit();
        }

        $req = $this->userManager->activateUser($request->getToken());

        if ($req === 1) {
            $this->session->setSession(['success' => "Votre inscription est définitivement validée, vous pouvez vous connecter."]);
            header('Location: /account/login#logintitle');
            exit();
        }

        // if token is no more valid
        if ($req === 2) {
            header('Location: /account/signin#signin');
            exit();
        }
       
        $this->session->setSession(['error' => "Impossible de confirmer votre compte, il y a un problème avec votre lien de confirmation, ou peut-être avez-vous déjà activé votre compte ?"]);
        header('Location: /account/signin#signin');
        exit();
    }

    // Resend confirmation mail
    public function resendMail(): void
    {
        // access control, check is user is not logged
        if ($this->auth->isLogged() === true) {
            $this->session->setSession(['error' => "Vous êtes déjà connecté(e) et avez donc un compte"]);
            header('Location: /posts/1');
            exit();
        }
        $previousToken = $this->session->getSession()['previousToken'];
        $req = $this->userManager->signinUserFromToken($previousToken);

        if ($req !== null) {
            $dest = $req['dest'];
            $token = $req['token'];

            //create a boundary for the email.
            $boundary = uniqid('np');

            $headers = [
                'From' => $this->config->contactMail,
                'X-Mailer' => 'PHP/' . phpversion(),
                'MIME-Version' => '1.0',
                'Content-type' => "multipart/alternative;boundary=\"" . $boundary . "\""
            ];
            
            // rendering html content of mail with twig
            $subject = $this->renderer->renderMail('FrontOffice/SigninMail.twig', 'subject');
            $message = $this->renderer->renderMail('FrontOffice/SigninMail.twig', 'message', [ 'token' => $token, 'boundary' => $boundary, 'basepath' => $this->config->serverUrl ]);
            
            // send mail
            if (mail($dest, $subject, $message, $headers) === true) {
                $this->session->setSession(['success' => "Votre inscription a de nouveau été enregistrée, vous allez recevoir un nouveau mail pour valider votre inscription."]);
                header('Location: /account/login#logintitle');
                exit();
            };
        }

        header('Location: /account/signin#signin');
        exit();
    }
}
