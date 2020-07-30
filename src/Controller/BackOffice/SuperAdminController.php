<?php
declare(strict_types=1);

namespace App\Controller\BackOffice;

use \App\View\View;
use \App\Model\Repository\UserRepository;
use \App\Model\Manager\UserManager;
use \App\Model\Entity\User;
use \App\Service\Http\Request;
use \App\Service\Http\Session;
use \App\Service\Auth;

class SuperAdminController
{
    private $renderer;
    private $userManager;
    private $userRepo;
    private $session;
    private $auth;

    public function __construct()
    {
        $this->renderer = new View();
        $this->userRepo = new UserRepository();
        $this->userManager = new UserManager($this->userRepo);
        $this->session = new Session();
        $this->auth = new Auth();
    }

    public function showUsersManager(Request $request): void
    {
        // access control, check is user is logged and superadmin
        if ($this->auth->isLogged() === false) {
            header("Location: /account/login#logintitle");
            exit();
        }
        $user = $this->auth->user();
        if ($this->auth->isSuperAdmin($user->getUserId()) === false) {
            header("Location: /admin/posts/1");
            exit();
        }
        
        $userPage=$request->getUserManagerPage();

        $totalUsers = $this->userManager->getNumberOfUsers();
        if ($totalUsers > 0) {
            $pagerArray = $this->userManager->getUsersManagerPager($userPage, $totalUsers);
            $offset = $pagerArray['offset'];
            $limit = $pagerArray['limit'];
            $totalUserPages = $pagerArray['totalUsersPages'];
            $userPage = $pagerArray['userPage'];
            
            // getting the Members from DB
            $listUsers = $this->userManager->getUsersPage((int)$offset, $limit);
        }
        
        $this->renderer->render('BackOffice/UsersManager.twig', [
            'listUsers' => isset($listUsers) ? $listUsers : null,
            'currentPage' => $userPage,
            'totalPages' => isset($totalUserPages) ? $totalUserPages : null,
            'session' => $this->session->getSession(),
            'user' => $user
            ]);
        $this->session->remove('success')->remove('error');
    }

    public function promote(Request $request): void
    {
        // access control, check is user is logged and superadmin
        if ($this->auth->isLogged() === false) {
            header("Location: /account/login#logintitle");
            exit();
        }
        if ($this->auth->isSuperAdmin($this->auth->user()->getUserId()) === false) {
            header("Location: /admin/posts/1");
            exit();
        }

        $user=$request->getUserId();
        if ($this->userManager->promoteUser($user) === true) {
            $this->session->setSession(['success' => "Droits admin donnés à l'utilisateur."]);
            header("Location: /super/members/1");
            exit();
        }
        header("Location: /super/members/1");
        exit();
    }

    public function demote(Request $request): void
    {
        // access control, check is user is logged and superadmin
        if ($this->auth->isLogged() === false) {
            header("Location: /account/login#logintitle");
            exit();
        }
        if ($this->auth->isSuperAdmin($this->auth->user()->getUserId()) === false) {
            header("Location: /admin/posts/1");
            exit();
        }
        
        $user=$request->getUserId();
        if ($this->userManager->demoteUser($user) === true) {
            $this->session->setSession(['success' => "Droits admin retirés à l'utilisateur."]);
            header("Location: /super/members/1");
            exit();
        }
        header("Location: /super/members/1");
        exit();
    }
}
