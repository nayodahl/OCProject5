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
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isSuperAdmin($userId) === false) {
            header("location: ../../admin/posts/1");
            exit();
        }
        
        $userPage=$request->getUserManagerPage();

        $totalUsers = $this->userManager->getNumberOfUsers();
        $pagerArray = $this->userManager->getUsersManagerPager($userPage, $totalUsers);
        $offset = $pagerArray['offset'];
        $limit = $pagerArray['limit'];
        $totalUserPages = $pagerArray['totalUsersPages'];
        $userPage = $pagerArray['userPage'];
        
        // getting the Members from DB
        $listUsers = $this->userManager->getUsersPage((int)$offset, $limit);
        
        $this->renderer->render('backoffice/UsersManager.twig', [
            'listUsers' => $listUsers,
            'currentPage' => $userPage,
            'totalPages' => $totalUserPages,
            'session' => $this->session->getSession(),
            'user' => $user
            ]);
        $this->session->remove('success')->remove('error');
    }

    public function promote(Request $request): void
    {
        // access control, check is user is logged and superadmin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isSuperAdmin($userId) === false) {
            header("location: ../../admin/posts/1");
            exit();
        }

        $user=$request->getUserId();
        if ($this->userManager->promoteUser($user) === true) {
            $this->session->setSession(['success' => "Droits admin donnés à l'utilisateur."]);
            header("location: ../../super/members/1");
            exit();
        }
        header("location: ../../super/members/1");
        exit();
    }

    public function demote(Request $request): void
    {
        // access control, check is user is logged and superadmin
        $user = $this->auth->user();
        $userId = ($user !== null) ? $user->getUserId() : null;
        if ($userId === null) {
            header("location: ../../account/login#login");
            exit();
        }
        if ($this->auth->isSuperAdmin($userId) === false) {
            header("location: ../../admin/posts/1");
            exit();
        }
        
        $user=$request->getUserId();
        if ($this->userManager->demoteUser($user) === true) {
            $this->session->setSession(['success' => "Droits admin retirés à l'utilisateur."]);
            header("location: ../../super/members/1");
            exit();
        }
        header("location: ../../super/members/1");
        exit();
    }
}
