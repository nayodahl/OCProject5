<?php
declare(strict_types=1);

namespace App\Model\Manager;

use \App\Model\Entity\Post;
use \App\Model\Repository\PostRepository;
use \App\Service\Http\Session;
use \App\Service\Http\Request;

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
        $offset = (int)(($commentPage - 1) * $limit); // offset, to determine the number of the first Comment to display
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
        $offset = (int)(($currentPage - 1) * $limit); // offset, to determine the number of the first Post to display

        return ['offset' => $offset, 'limit' => $limit, 'totalPages' => $totalPages, 'currentPage' => $currentPage];
    }

    public function getNumberOfPosts(): int
    {
        // get the total number of posts, needed for pager calculation
        return $this->postRepo->countPosts();
    }

    public function modifyPostContent(int $postId, ?string $title, ?string $chapo, ?int $authorId, ?string $content): bool
    {
        // check if input is valid
        $badTitle = ($title === null) || (mb_strlen($title) > Request::MAX_STRING_LENGTH) || (mb_strlen($title) < Request::MIN_STRING_LENGTH);
        $badChapo = ($chapo == null) || (mb_strlen($chapo) > Request::MAX_STRING_LENGTH) || (mb_strlen($chapo) < Request::MIN_STRING_LENGTH);
        $badContent = ($content == null) || (mb_strlen($content) > Request::MAX_TEXTAREA_LENGTH) || (mb_strlen($content) < Request::MIN_TEXTAREA_LENGTH);
        if ($badTitle || $badChapo || ($authorId === null) || $badContent) {
            $this->session->setSession(['error' => "Champ(s) vide(s) ou trop long(s) ou auteur invalide."]);
            return false;
        }
        
        if ($this->postRepo->updatePost($postId, $title, $chapo, $authorId, $content) === false) {
            $this->session->setSession(['error' => "Impossible de modifier l'article : identifiant d'article ou d'auteur invalide ou erreur à l'enregistrement."]);
            return false;
        }
        
        return true;
    }

    public function createPost(?string $title, ?string $chapo, ?int $authorId, ?string $content): ?int
    {
        $badTitle = ($title === null) || (mb_strlen($title) > Request::MAX_STRING_LENGTH) || (mb_strlen($title) < Request::MIN_STRING_LENGTH);
        $badChapo = ($chapo == null) || (mb_strlen($chapo) > Request::MAX_STRING_LENGTH) || (mb_strlen($chapo) < Request::MIN_STRING_LENGTH);
        $badContent = ($content == null) || (mb_strlen($content) > Request::MAX_TEXTAREA_LENGTH) || (mb_strlen($content) < Request::MIN_TEXTAREA_LENGTH);
        if ($badTitle || $badChapo || ($authorId === null) || $badContent) {
            $this->session->setSession(['error' => "Champ(s) vide(s) ou trop long(s) ou auteur invalide."]);
            return null;
        }
        
        $req = $this->postRepo->addPost($title, $chapo, $authorId, $content);
        if ($req === null) {
            $this->session->setSession(['error' => "Impossible de publier l'article : auteur invalide ou erreur à l'enregistrement."]);
            return null;
        }

        return $req;
    }

    public function deletePost(int $postId): bool
    {
        if ($this->postRepo->deleteOnePost($postId) === false) {
            $this->session->setSession(['error' => "Impossible de supprimer l'article : identifiant d'article invalide ou erreur à la suppression."]);
            return false;
        }
        return true;
    }
}
