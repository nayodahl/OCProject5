<?php
declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\Post;
use App\Model\Repository\PostRepository;

class PostManager
{
    private $postRepo;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepo = $postRepository;
    }
    
    public function showSinglePost(int $postId): ?Post
    {
        $data = $this->postRepo->getPost($postId);

        // réfléchir à l'hydratation des entités
        /*
        $post = new \App\Model\Entity\Post();
        $post
            ->setPostId((int)($data['id'])) // pourquoi je dois convertir en int ?
            ->setTitle($data['title'])
            ->setChapo($data['chapo'])
            ->setContent($data['content'])
            ->setCreated($data['creation_date_fr'])
            ->setLastUpdate($data['update_date_fr'])
            ->setAuthorId($data['user_id'])
            ->setAuthorLogin($data['login'])
        ;
        */
        return $data;
    }
}
