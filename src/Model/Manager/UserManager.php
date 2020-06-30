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
    
    /*
    public function getSingleUser(int $userId): ?User
    {
        return $this->userRepo->getUser($userId);
    }
    */
}
