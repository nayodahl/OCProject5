<?php
declare(strict_types=1);

namespace App\Service\Http;

class Request
{
    private $get;
    private $post;
    
    public function __construct()
    {
        $this->get = null;
        $this->post = null;
        if (isset($_GET['url'])) {
            $this->get = $_GET;
            $this->get = explode('/', $this->get['url']);
        }

        if (isset($_POST)) {
            $this->post = $_POST;
        }
    }

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
