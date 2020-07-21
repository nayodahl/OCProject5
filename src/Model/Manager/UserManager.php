<?php
declare(strict_types=1);

namespace App\Model\Manager;

use \App\Model\Entity\User;
use \App\Model\Repository\UserRepository;
use \App\Service\Http\Session;
use \App\Service\Http\Request;

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
        if ($this->userRepo->updateUserType($userId, 'admin') === false) {
            $this->session->setSession(['error' => "Impossible de donner les droits admin à l'utilisateur : identifiant d'utilisateur invalide ou erreur à l'enregistrement."]);
            return false;
        }
        
        return true;
    }

    public function demoteUser(int $userId): bool
    {
        // if user is still the author of Post then we forbid the demote
        if ($this->userRepo->userHasPosts($userId) === true) {
            $this->session->setSession(['error' => "Impossible de retirer les droits admin à l'utilisateur. Veuillez vérifier s'il est encore l'auteur d'un ou plusieurs article(s)."]);
            return false;
        }
        if ($this->userRepo->updateUserType($userId, 'member') === false) {
            $this->session->setSession(['error' => "Impossible de retirer les droits admin à l'utilisateur : identifiant d'utilisateur invalide ou erreur à l'enregistrement."]);
            return false;
        }
        return true;
    }

    public function login(?string $login, ?string $password): ?User
    {
        // check if input is valid
        if (($login === null) || (mb_strlen($login) > Request::MAX_LOGIN_LENGTH) || (mb_strlen($login) < Request::MIN_LOGIN_LENGTH)
            || ($password == null) || (mb_strlen($password) > Request::MAX_STRING_LENGTH) || (mb_strlen($password) < Request::MIN_PASSWORD_LENGTH)) {
            $this->session->setSession(['error' => "Identifiant ou mot de passe vide ou pas de la bonne longueur (entre 3 et 16 caractères alphanumériques pour le login, 8 caractères minimum pour le mot de passe)."]);
                
            return null;
        }
        $user = $this->userRepo->checkLogin($login, $password);
        if ($user === null) {
            $this->session->setSession(['error' => "Identifiant ou mot de passe incorrect, ou utilisateur non activé (vérifiez vos mails)."]);

            return null;
        }
        $this->session->setSession(['auth' => $user->getUserId(), 'success' => "Connexion réussie."]);
        return $user;
    }

    public function activateUser(string $token): bool
    {
        if ($this->userRepo->searchToken($token) === true) {
            return $this->userRepo->activateOneUser($token);
        }

        return false;
    }

    public function signin(?string $login, ?string $password, ?string $email): ?array
    {
        // check if input is valid
        if (($login === null) || (mb_strlen($login) > Request::MAX_LOGIN_LENGTH) || (mb_strlen($login) < Request::MIN_LOGIN_LENGTH)
        || ($password == null) || (mb_strlen($password) > Request::MAX_STRING_LENGTH) || (mb_strlen($password) < Request::MIN_PASSWORD_LENGTH)
        || ($email == null) || (mb_strlen($email) > Request::MAX_STRING_LENGTH) || (filter_var($email, FILTER_VALIDATE_EMAIL) === false)) {
            $this->session->setSession(['error' => "Tous les champs ne sont pas remplis ou corrects (entre 3 et 16 caractères alphanumériques pour le login, 8 caractères minimum pour le mot de passe, avec au moins une majuscule, au moins une minuscule, au moins un chiffre et au moins un caractère spécial)."]);
            return null;
        }
        
        // check if user already exists
        if (($this->userRepo->userExists($login)) === true) {
            $this->session->setSession(['error' => "Ce login est déjà utilisé"]);
            return null;
        }
        
        // Regex for username
        $regex = '/^[a-z0-9_-]{3,15}$/';
        if (preg_match($regex, $login) === 0) {
            $this->session->setSession(['error' => "Le login ne respecte pas les règles de complexité : entre 3 et 16 caractères alphanumériques."]);
            return null;
        }

        // Regex for password, exemple here https://ihateregex.io/expr/password
        $regex = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/';
        if (preg_match($regex, $password) === 0) {
            $this->session->setSession(['error' => "Le mot de passe ne respecte pas les règles de complexité : minimum 8 caractères, au moins une majuscule, au moins une minuscule, au moins un chiffre et au moins un caractère spécial."]);
            return null;
        }

        // hash password, using random salt generation
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // create new user with not activated status
        $newUserId = $this->userRepo->addUser($login, $passwordHash, $email);

        // generate token (length 128) and insert in User
        $token = bin2hex(random_bytes(64));
        if ($this->userRepo->insertToken($token, $newUserId) === false) {
            $this->session->setSession(['error' => "Impossible de générer le token"]);
            return null;
        }
        $user = $this->userRepo->getUser($newUserId);
        $dest = $user->getEmail();

        return [ 'dest' => $dest, 'token' => $token];
    }
}
