<?php
declare(strict_types=1);

namespace App\Service;

use \App\Service\Http\Request;
use \App\Service\Http\Session;
use \App\Model\Repository\UserRepository;
use \App\Model\Entity\User;

class Auth
{
    private $request;
    private $session;
    private $userRepo;
    
    public function __construct()
    {
        // dependancies
        $this->request = new Request();
        $this->session = new Session();
        $this->userRepo = new UserRepository();
    }

    public function user(): ?User
    {
        $userId = $this->session->getSession()['auth'] ?? null;
        if ($userId === null) {
            $this->session->setSession(['error' => "Vous devez être connecté"]);
            return null;
        }
        return $this->userRepo->getUser($userId) ?: null;
    }

    public function isAdmin($userId): bool
    {
        $user = $this->userRepo->getUser($userId);
        if (($user === false) || ($user->getType() === 'member')) {
            $this->session->setSession([
                'error' => "Action ou section réservée aux Administrateurs.",
                'auth' => $user->getUserId()
            ]);
            return false;
        }
        
        return true;
    }

    public function isSuperAdmin($userId): bool
    {
        $user = $this->userRepo->getUser($userId);
        if (($user === false) || ($user->getType() !== 'superadmin')) {
            $this->session->setSession([
                'error' => "Action ou section réservée aux SuperAdministrateurs.",
                'auth' => $user->getUserId()
            ]);
            return false;
        }
        
        return true;
    }
}
