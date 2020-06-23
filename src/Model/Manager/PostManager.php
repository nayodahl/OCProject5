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
        $postsList = $this->postRepo->getAllPosts();
        $key = null;
        foreach ($postsList as $post) {
            if ($postId === $post->getPostId()) {
                $key = $post;
                break;
            }
        }
        $prev = array_search($key, $postsList, true)-1;
        if ($prev >= 0) {
            return $postsList[$prev]->getPostId();
        }
        return null;
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
        return $this->postRepo->countPosts();
    }
}
