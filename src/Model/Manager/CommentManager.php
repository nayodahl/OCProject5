<?php
declare(strict_types=1);

namespace App\Model\Manager;

use \App\Model\Entity\Comment;
use \App\Model\Repository\CommentRepository;
use \App\Service\Http\Request;
use \App\Service\Http\Session;

class CommentManager
{
    private $commentRepo;
    private $session;

    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepo = $commentRepo;
        $this->session = new Session();
    }

    public function getApprovedComments(int $postId, int $offset, int $commentsNumberLimit): array
    {
        // last parameter is the status of the comment, 1 for approved
        return $this->commentRepo->getCommentsFromPost($postId, $offset, $commentsNumberLimit, 1);
    }

    public function getNotApprovedComments(int $offset, int $commentsNumberLimit): array
    {
        return $this->commentRepo->getAllNotApprovedComments($offset, $commentsNumberLimit);
    }

    public function getNumberOfApprovedCommentsFromPost(int $postId): int
    {
        // get the total number of comments, needed for pager calculation, that are approved for one Post
        return $this->commentRepo->countNumberOfApprovedCommentsFromPost($postId);
    }

    public function getNumberOfApprovedComments(): int
    {
        // get the total number of comments, needed for pager calculation, that are approved
        return $this->commentRepo->countComments(1);
    }

    public function getNumberofNotApprovedComments(): int
    {
        // get the total number of comments, needed for pager calculation, that are not approved
        return $this->commentRepo->countComments(0);
    }

    public function getCommentsManagerPager(int $commentPage, int $totalComments): array
    {
        // Some calculation for the pager for Posts page
        $limit = 50; // number of Posts per page to display
        $totalCommentPages = ceil($totalComments / $limit);
        if ($commentPage > $totalCommentPages) {
            $commentPage=$totalCommentPages; //correcting user input
        };
        $offset = (int)(($commentPage - 1) * $limit); // offset, to determine the number of the first Post to display

        return ['offset' => $offset, 'limit' => $limit, 'totalCommentPages' => $totalCommentPages, 'commentPage' => $commentPage];
    }

    public function addCommentToPost(int $postId, int $authorId, string $comment): bool
    {
        if (($comment === '') || (mb_strlen($comment) > Request::MAX_TEXTAREA_LENGTH) || (mb_strlen($comment) < Request::MIN_TEXTAREA_LENGTH)) {
            $this->session->setSession(['error' => "Commentaire vide ou trop long."]);
            return false;
        }
        
        $req = $this->commentRepo->insertCommentToPost($postId, $authorId, $comment);
        if ($req === false) {
            $this->session->setSession(['error' => "Erreur inconnue, impossible d'ajouter le commentaire."]);
            return false;
        }
        
        return $req;
    }

    public function approveComment(int $commentId): bool
    {
        if ($this->commentRepo->setCommentToApproved($commentId) === false) {
            $this->session->setSession(['error' => "Impossible d'approuver le commentaire : identifiant de commentaire invalide ou erreur à l'enregistrement."]);
            return false;
        }
        
        return true;
    }

    public function refuseComment(int $commentId): bool
    {
        if ($this->commentRepo->deleteComment($commentId) === false) {
            $this->session->setSession(['error' => "Impossible de supprimer le commentaire : identifiant de commentaire invalide ou erreur à la suppression."]);
            return false;
        }
        
        return true;
    }
}
