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

    public function getSinglePostPager(int $postId, int $commentPage, int $totalComments): array
    {
        $limit = 4; // number of Comments per page to display
        $totalCommentPages = ceil($totalComments / $limit);
        if ($commentPage > $totalCommentPages) {
            $commentPage=$totalCommentPages; //correcting user input
        };
        $offset = ($commentPage - 1) * $limit; // offset, to determine the number of the first Comment to display
        if ($offset < 0) {
            $offset = 0;
        };
        return [$offset, $limit, $totalCommentPages, $commentPage];
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

    public function getPostsPagePager(int $currentPage, int $totalItems): array
    {
        // Some calculation for the pager for Posts page
        $limit = 4; // number of Posts per page to display
        $totalPages = ceil($totalItems / $limit);
        if ($currentPage > $totalPages) {
            $currentPage=$totalPages; //correcting user input
        };
        $offset = ($currentPage - 1) * $limit; // offset, to determine the number of the first Post to display

        return [$offset, $limit, $totalPages, $currentPage];
    }

    public function getNumberOfPosts(): int
    {
        // get the total number of posts, needed for pager calculation
        return $this->postRepo->countPosts();
    }
}
