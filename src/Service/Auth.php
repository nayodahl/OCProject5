<?php
declare(strict_types=1);

namespace App\Service;

use \App\Service\Http\Session;
use \App\Model\Repository\UserRepository;
use \App\Model\Entity\User;

class Auth
{
    private $session;
    private $userRepo;
    
    public function __construct(Session $session, UserRepository $userRepo)
    {
        $this->session = $session;
        $this->userRepo = $userRepo;
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

    public function isLogged(): bool
    {
        if ($this->user() === null) {
            return false;
        }
        
        return true;
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

    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(64));
        $this->session->setSession(['token' => $token]);
        
        return $token;
    }

    public function checkToken(?string $token): bool
    {
        if (isset($this->session->getSession()['token']) && !empty($this->session->getSession()['token']) && !empty($token)) {
            if ($this->session->getSession()['token'] === $token) {
                return true;
            }
        }
        
        return false;
    }
}
