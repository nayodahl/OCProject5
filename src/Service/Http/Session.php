<?php
declare(strict_types=1);

namespace App\Service\Http;

class Session
{
    private $session;
    
    public function __construct()
    {
        session_start();
        $this->session = null;   
        if (isset($_SESSION)) {$this->session = $_SESSION;}
    }

    public function getSession(): ?array
    {
        return $this->session;
    }

    public function setSession(array $session): self
    {
        $this->session = $session;
        $_SESSION = $session;

        return $this;
    }
    
    public function remove(string $session): self
    {
        unset($this->session[$session]);
        unset($_SESSION[$session]); 

        return $this;
    }
}
