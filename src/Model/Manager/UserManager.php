<?php
declare(strict_types=1);

namespace App\Model\Manager;

use \App\Model\Entity\User;
use \App\Model\Repository\UserRepository;
use \App\Service\Http\Session;

class UserManager
{
    private $userRepo;
    private $session;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
        $this->session = new Session();
    }

    public function getUsersPage(int $offset, int $limit): array
    {
        return $this->userRepo->getNonSuperAdminUsers($offset, $limit);
    }

    public function getNumberOfUsers(): int
    {
        // get the total number of users, needed for pager calculation
        return $this->userRepo->countUsers();
    }
    
    public function getUsersManagerPager(int $userPage, int $totalUsers): array
    {
        // Some calculation for the pager for Users page
        $limit = 20; // number of Users per page to display
        $totalUsersPages = ceil($totalUsers / $limit);
        if ($userPage > $totalUsersPages) {
            $userPage=$totalUsersPages; //correcting user input
        };
        $offset = ($userPage - 1) * $limit; // offset, to determine the number of the first User to display

        return ['offset' => $offset, 'limit' => $limit, 'totalUsersPages' => $totalUsersPages, 'userPage' => $userPage];
    }

    public function getAdminUsers(): array
    {
        return $this->userRepo->getAllAdminUsers();
    }

    public function promoteUser(int $userId): bool
    {
        return $this->userRepo->updateUserType($userId, 'admin');
    }

    public function demoteUser(int $userId): bool
    {
        // if user is still the author of Post then we forbid the demote
        if ($this->userRepo->userHasPosts($userId)) {
            return false;
        }
        return $this->userRepo->updateUserType($userId, 'member');
    }

    public function login(string $login, string $password): ?User
    {
        return $this->userRepo->checkLogin($login, $password);
    }
}
