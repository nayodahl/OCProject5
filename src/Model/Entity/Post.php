<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Post
{
    private $id;
    private $title;
    private $chapo;
    private $content;
    private $created;
    private $lastUpdate;
    private $authorId;
    private $authorLogin;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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
