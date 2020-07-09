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

    
    /* signin processing
        - check user complexity and length, uses regex
        - check password complexity and length, uses regex
        - check if user exists (must be unique), needs repo
        - hash password
        - create user with status =  not activated
        - send mail with token
        if creation is successful, return the id of the new User, else null
    */
    public function signin(string $login, string $password, string $email): ?int
    {
        // Regex for username 
        $regex = '/^[a-z0-9_-]{3,15}$/';
        if (preg_match($regex, $login) === 0){
            $this->session->setSession(['error' => "Le login ne respecte pas les règles de complexité : entre 3 et 16 caractères alphanumériques."]);
            header('location: signin#signin');
            exit();
        }

        // Regex for password, exemple here https://ihateregex.io/expr/password
        $regex = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/';
        if (preg_match($regex, $password) === 0){
            $this->session->setSession(['error' => "Le mot de passe ne respecte pas les règles de complexité : minimum 8 caractères, au moins une majuscule, au moins une minuscule, au moins un chiffre et au moins un caractère spécial."]);
            header('location: signin#signin');
            exit();
        }

        // check if user already exists
        if (($this->userRepo->userExists($login)) === true){
            $this->session->setSession(['error' => "Ce login est déjà utilisé"]);
            header('location: signin#signin');
            exit();
        }
        // hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // create new user with not activated status
        $newUserId = $this->userRepo->addUser($login, $passwordHash, $email);
                
        return 1;
    }
}
