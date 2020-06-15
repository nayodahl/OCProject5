<?php
declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\User;
use App\Model\Repository\UserRepository;

class UserManager
{
    private $userRepo;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }
    
    /*
    public function getSingleUser(int $userId): ?User
    {
        return $this->userRepo->getUser($userId);
    }
    */
}
