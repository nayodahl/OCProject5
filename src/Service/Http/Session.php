<?php
declare(strict_types=1);

namespace App\Service\Http;

class Session
{
    private $session;
    
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->session = null;
        if (isset($_SESSION)) {
            $this->session = $_SESSION;
        }
    }

    public function getSession(): ?array
    {
        return $this->session;
    }

    public function setSession(array $addedArray): self
    {
        if (count($this->session) > 0) {
            $this->session = $_SESSION = array_merge($this->session, $addedArray);
            return $this;
        }

        $this->session = $_SESSION = $addedArray;
        return $this;
    }
    
    public function remove(string $session): self
    {
        unset($this->session[$session]);
        unset($_SESSION[$session]);

        return $this;
    }

    public function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
