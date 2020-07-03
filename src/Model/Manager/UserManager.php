<?php
declare(strict_types=1);

namespace App\Model\Manager;

use \App\Model\Entity\User;
use \App\Model\Repository\UserRepository;

class UserManager
{
    private $userRepo;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
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

        return [$offset, $limit, $totalUsersPages, $userPage];
    }

    public function getAdminUsers(): array
    {
        return $this->userRepo->getAllAdminUsers();
    }
}
