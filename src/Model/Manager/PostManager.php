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
        return $this->postRepo->getPost($postId);
    }

    public function getHomepagePosts(): array
    {
        // get only last 4 posts to display on homepage.
        return $this->postRepo->getMostXRecentPosts(4);
    }

    public function getPosts(): array
    {
        //  getting all posts and displaying on one single plage
        return $this->postRepo->getAllPosts();
    }

    public function getNumberOfPosts(): int
    {
        // get the total number of posts, needed for pager calculation
        return $this->postRepo->CountPosts();
    }
}
