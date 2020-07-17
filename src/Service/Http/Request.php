<?php
declare(strict_types=1);

namespace App\Service\Http;

use App\Service\FormValidator;

class Request
{
    public const MIN_LOGIN_LENGTH = 3;
    public const MAX_LOGIN_LENGTH = 16;
    public const MIN_PASSWORD_LENGTH = 8;
    public const MAX_STRING_LENGTH = 500;
    public const MIN_TEXTAREA_LENGTH = 1;
    public const MAX_TEXTAREA_LENGTH = 50000;
    
    private $get;
    private $post;
    private $formValidator;
      
    public function __construct()
    {
        $this->get = $this->post = null;
        
        if (isset($_GET['url'])) {
            $this->get = $_GET;
            $this->get = explode('/', $this->get['url']);
        }
        if (isset($_POST)) {
            $this->post = $_POST;
        }

        $this->formValidator = new FormValidator();
    }

    public function getPostId(): int
    {
        return (int)$this->get[1];
    }

    public function getEditPostId(): int
    {
        return (int)$this->get[2];
    }

    public function getPostsPage(): int
    {
        return (int)$this->get[1];
    }

    public function getPostsManagerPage(): int
    {
        return (int)$this->get[2];
    }

    public function getCommentPage(): int
    {
        return (int)$this->get[2];
    }

    public function getCommentManagerPage(): int
    {
        return (int)$this->get[2];
    }

    public function getCommentId(): int
    {
        return (int)$this->get[2];
    }

    public function getUserManagerPage(): int
    {
        return (int)$this->get[2];
    }

    public function getUserId(): int
    {
        return (int)$this->get[2];
    }

    public function getToken(): string
    {
        return $this->get[2];
    }

    public function getCommentFormData(): ?string
    {
        if ($this->post !== null) {
            $this->post = ['comment' => $this->sanitizeTextArea($this->post['comment'])];
            
            return $this->post['comment'];
        }
    }

    public function getLoginFormData(): ?array
    {
        if ($this->post !== null) {
            $this->post = [
                'login' => $this->sanitizeLogin($this->post['login']),
                'password' => $this->post['password']
        ];
            return [
                'login' => $this->post['login'],
                'password' => $this->post['password']
            ];
        }
    }

    public function getSigninFormData(): ?array
    {
        if ($this->post !== null) {
            $this->post = [
                'login' => $this->sanitizeLogin($this->post['login']),
                'password' => $this->post['password'],
                'email' => $this->sanitizeEmail($this->post['email'])
        ];
            return [
                'login' => $this->post['login'],
                'password' => $this->post['password'],
                'email' => $this->post['email']
            ];
        }
    }

    public function getPostFormData(): ?array
    {
        if ($this->post !== null) {
            $this->post = [
                'title' => $this->formValidator->sanitizeString($this->post['title']),
                'chapo' => $this->formValidator->sanitizeString($this->post['chapo']),
                'author' => $this->formValidator->sanitizeInteger((int)$this->post['author']),
                'content' => $this->formValidator->sanitizeTextArea($this->post['content'])
                
            ];
            return [
                'title' => $this->post['title'],
                'chapo' => $this->post['chapo'],
                'author' => $this->post['author'],
                'content' => $this->post['content']
            ];
        }
    }

    public function getContactFormData(): ?array
    {
        if ($this->post !== null) {
            $this->post = [
                'lastname' => $this->sanitizeString($this->post['lastname']),
                'firstname' => $this->sanitizeString($this->post['firstname']),
                'email' => $this->sanitizeEmail($this->post['email']),
                'message' => $this->sanitizeTextArea($this->post['message'])
            ];
        }
        return [
            'lastname' => $this->post['lastname'],
            'firstname' => $this->post['firstname'],
            'email' => $this->post['email'],
            'message' => $this->post['message']
        ];
    }
    
    // cleanup methods
    
    public function sanitizeString(string $data): string
    {
        $data = trim($data);
        $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        $data = htmlspecialchars($data);

        return $data;
    }

    public function sanitizeTextArea(string $data): string
    {
        return htmlspecialchars($data);
    }

    public function sanitizeEmail(string $data): ?string
    {
        return filter_var($data, FILTER_SANITIZE_EMAIL);
    }

    public function sanitizeLogin(string $data): string
    {
        $data = trim($data);
        $data = htmlspecialchars($data);

        return $data;
    }


    //// getters and setters

    public function getGet(): ?array
    {
        return $this->get;
    }

    public function setGet(array $get): self
    {
        $this->get = $get;
        return $this;
    }

    public function getPost(): ?array
    {
        return $this->post;
    }

    public function setPost(array $post): self
    {
        $this->post = $post;
        return $this;
    }
}
