<?php
declare(strict_types=1);

namespace App\Model\Manager;

use \App\Model\Entity\Post;
use \App\Model\Repository\PostRepository;
use \App\Service\Http\Session;

class PostManager
{
    private $postRepo;
    private $session;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepo = $postRepository;
        $this->session = new Session();
    }
    
    public function getSinglePost(int $postId): ?Post
    {
        $req = $this->postRepo->getPost($postId);
        if ($req === null) {
            $this->session->setSession(['error' => "Numéro d'article invalide"]);
            return null;
        }
        
        return $req;
    }

    public function getSinglePostPager(int $commentPage, int $totalComments): array
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
        
        return ['offset' => $offset, 'limit' => $limit, 'totalCommentPages' => $totalCommentPages, 'commentPage' => $commentPage];
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

        return ['offset' => $offset, 'limit' => $limit, 'totalPages' => $totalPages, 'currentPage' => $currentPage];
    }

    public function getNumberOfPosts(): int
    {
        // get the total number of posts, needed for pager calculation
        return $this->postRepo->countPosts();
    }

    public function modifyPostContent(int $postId, string $title, string $chapo, int $authorId, string $content): bool
    {
        return $this->postRepo->updatePost($postId, $title, $chapo, $authorId, $content);
    }

    public function createPost(string $title, string $chapo, int $authorId, string $content): ?int
    {
        return $this->postRepo->addPost($title, $chapo, $authorId, $content);
    }

    public function deletePost(int $postId): bool
    {
        return $this->postRepo->deleteOnePost($postId);
    }
}
