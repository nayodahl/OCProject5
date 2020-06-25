<?php
declare(strict_types=1);

namespace App\Model\Manager;

use \App\Model\Entity\Post;
use \App\Model\Repository\PostRepository;

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

    // get next Post id, based on their creation date, else null
    public function getNextPostId(int $postId): ?int
    {
        return $this->postRepo->getNextId($postId);
    }

    // get previous Post id, based on their creation date, else null
    public function getPreviousPostId(int $postId): ?int
    {
        return $this->postRepo->getPrevId($postId);
    }

    public function getHomepagePosts(): array
    {
        // get only last 4 posts to display on homepage.
        return $this->postRepo->getMostXRecentPosts(4);
    }

    public function getPostsPage(int $offset, int $limit): array
    {
        //  getting all posts and displaying on one single page
        return $this->postRepo->getPosts($offset, $limit);
    }

    public function getNumberOfPosts(): int
    {
        // get the total number of posts, needed for pager calculation
        return $this->postRepo->countPosts();
    }
}
