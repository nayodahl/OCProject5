<?php
declare(strict_types=1);

namespace App\Model\Manager;

use \App\Model\Entity\Comment;
use \App\Model\Repository\CommentRepository;

class CommentManager
{
    private $commentRepo;

    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepo = $commentRepo;
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
        $offset = ($commentPage - 1) * $limit; // offset, to determine the number of the first Post to display

        return [$offset, $limit, $totalCommentPages, $commentPage];
    }

    public function addCommentToPost(int $postId, int $authorId, string $comment): bool
    {
        return $this->commentRepo->insertCommentToPost($postId, $authorId, $comment);
    }

    public function approveComment(int $commentId): bool
    {
        return $this->commentRepo->setCommentToApproved($commentId);
    }

    public function refuseComment(int $commentId): bool
    {
        return $this->commentRepo->deleteComment($commentId);
    }
}
