<?php
declare(strict_types=1);

namespace App\Service\Http;

class Request
{
    public const MIN_LOGIN_LENGTH = 3;
    public const MAX_LOGIN_LENGTH = 16;
    public const MIN_PASSWORD_LENGTH = 8;
    public const MIN_STRING_LENGTH = 1;
    public const MAX_STRING_LENGTH = 500;
    public const MIN_TEXTAREA_LENGTH = 1;
    public const MAX_TEXTAREA_LENGTH = 50000;
    
    private $get;
    private $post;
      
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
        // if not defined, return 1
        return isset($this->get[2]) ? (int)$this->get[2] : 1;
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

    public function getCommentFormData(): ?array
    {
        if (isset($this->post) && !empty($this->post)) {
            $this->post = [
                'comment' => $this->sanitizeTextArea($this->post['comment']),
                'token' => $this->post['token']
        ];
            return [
                'comment' => $this->post['comment'],
                'token' => $this->post['token']
            ];
        }
        return null;
    }

    public function getLoginFormData(): ?array
    {
        if (isset($this->post) && !empty($this->post)) {
            $this->post = [
                'login' => $this->sanitizeLogin($this->post['login']),
                'password' => $this->post['password'],
                'token' => $this->post['token']
        ];
            return [
                'login' => $this->post['login'],
                'password' => $this->post['password'],
                'token' => $this->post['token']
            ];
        }
        return null;
    }

    public function getSigninFormData(): ?array
    {
        if (isset($this->post) && !empty($this->post)) {
            $this->post = [
                'login' => $this->sanitizeLogin($this->post['login']),
                'password' => $this->post['password'],
                'confirm' => $this->post['confirm'],
                'email' => $this->sanitizeEmail($this->post['email']),
                'token' => $this->post['token']
        ];
            return [
                'login' => $this->post['login'],
                'password' => $this->post['password'],
                'confirm' => $this->post['confirm'],
                'email' => $this->post['email'],
                'token' => $this->post['token']
            ];
        }
        return null;
    }

    public function getPostFormData(): ?array
    {
        if (isset($this->post) && !empty($this->post)) {
            $this->post = [
                'title' => $this->sanitizeString($this->post['title']),
                'chapo' => $this->sanitizeString($this->post['chapo']),
                'author' => $this->sanitizeInteger((int)$this->post['author']),
                'content' => $this->sanitizeTextArea($this->post['content']),
                'token' => $this->post['token']
                
            ];
            return [
                'title' => $this->post['title'],
                'chapo' => $this->post['chapo'],
                'author' => $this->post['author'],
                'content' => $this->post['content'],
                'token' => $this->post['token']
            ];
        }
        return null;
    }

    public function getContactFormData(): ?array
    {
        if (isset($this->post) && !empty($this->post)) {
            $this->post = [
                'lastname' => $this->sanitizeString($this->post['lastname']),
                'firstname' => $this->sanitizeString($this->post['firstname']),
                'email' => $this->sanitizeEmail($this->post['email']),
                'message' => $this->sanitizeTextArea($this->post['message']),
                'token' => $this->post['token']
            ];
        }
        return [
            'lastname' => $this->post['lastname'],
            'firstname' => $this->post['firstname'],
            'email' => $this->post['email'],
            'message' => $this->post['message'],
            'token' => $this->post['token']
        ];
        return null;
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
        return strip_tags($data, '<p><a><ul><li><hr><blockquote><b><i><u><br>');
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

    public function sanitizeInteger(int $value): ?int
    {
        if (is_int($value) && !empty($value)) {
            return $value;
        }
        return null;
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
