<?php
declare(strict_types=1);

namespace App\Service\Http;

use App\Service\FormValidator;

class Request
{
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
        
        /* Strip query string (?a=b) from Request Url
        if (($strpos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $strpos);
        }
        */
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
        if ($this->post != null) {
            $this->post = ['comment' => FormValidator::sanitizeTextArea($this->post['comment'])];
            return $this->post['comment'];
        }
        return null;
    }

    public function getLoginFormData(): ?array
    {
        if ($this->post != null) {
            $this->post = [
                'login' => FormValidator::sanitizeLogin($this->post['login']),
                'password' => FormValidator::sanitizePassword($this->post['password'])
        ];
            return [
                'login' => $this->post['login'],
                'password' => $this->post['password']
            ];
        }
        return null;
    }

    public function getSigninFormData(): ?array
    {
        if ($this->post != null) {
            $this->post = [
                'login' => FormValidator::sanitizeLogin($this->post['login']),
                'password' => FormValidator::sanitizePassword($this->post['password']),
                'email' => FormValidator::sanitizeEmail($this->post['email']),
                'isEmail' => FormValidator::isEmail($this->post['email'])
        ];
            return [
                'login' => $this->post['login'],
                'password' => $this->post['password'],
                'email' => $this->post['email'],
                'isEmail' => $this->post['isEmail']
            ];
        }
        return null;
    }


    public function getPostFormData(): ?array
    {
        if ($this->post != null) {
            $this->post = [
                'title' => FormValidator::sanitizeString($this->post['title']),
                'chapo' => FormValidator::sanitizeString($this->post['chapo']),
                'author' => FormValidator::sanitizeInteger((int)$this->post['author']),
                'content' => FormValidator::sanitizeTextArea($this->post['content'])
                
            ];
            return [
                'title' => $this->post['title'],
                'chapo' => $this->post['chapo'],
                'author' => $this->post['author'],
                'content' => $this->post['content']
            ];
        }
        return null;
    }

    public function getContactFormData(): ?array
    {
        if ($this->post != null) {
            $this->post = [
                'lastname' => FormValidator::sanitizeString($this->post['lastname']),
                'firstname' => FormValidator::sanitizeString($this->post['firstname']),
                'email' => FormValidator::sanitizeEmail($this->post['email']),
                'message' => FormValidator::sanitizeTextArea($this->post['message']),
                'isEmail' => FormValidator::isEmail($this->post['email'])
            ];
        }
        return [
            'lastname' => $this->post['lastname'],
            'firstname' => $this->post['firstname'],
            'email' => $this->post['email'],
            'message' => $this->post['message'],
            'isEmail' => $this->post['isEmail']
        ];
        
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
