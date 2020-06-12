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
    
    public function getSinglePost(int $postId): ?Post
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

    public function getHomepagePosts(): array
    {
        // get only last 4 posts to display on homepage.
        $data = $this->postRepo->getMostXRecentPosts(4);

        return $data;
    }

    public function getPosts(): array
    {
        // for the moment, getting all 100 posts and displaying on one single plage
        // waiting for pager system
        $data = $this->postRepo->getMostXRecentPosts(100);

        return $data;
    }
}
