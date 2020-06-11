<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Post
{
    private $postId;
    private $title;
    private $chapo;
    private $content;
    private $created;
    private $lastUpdate;
    private $authorId;
    private $authorLogin;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    // for each attribute we get from DB, we try to call a method with the same name, starting with 'set"
    public function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set'.ucwords($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getPostId(): int
    {
        return ((int)($this->postId)); // pourquoi je dois convertir le résultat en int ?
    }

    public function setPostId($postId): self //pourquoi on ne peut pas forcer le typage du paramètre en int ?
    {
        $this->postId = $postId;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getChapo(): string
    {
        return $this->chapo;
    }

    public function setChapo(string $chapo): self
    {
        $this->chapo = $chapo;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function setCreated(string $created): self
    {
        $this->created = $created;
        return $this;
    }

    public function getLastUpdate(): string
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(string $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function setAuthorId(string $authorId): self
    {
        $this->authorId = $authorId;
        return $this;
    }

    public function getAuthorLogin(): string
    {
        return $this->authorLogin;
    }

    public function setAuthorLogin(string $authorLogin): self
    {
        $this->authorLogin = $authorLogin;
        return $this;
    }
}
