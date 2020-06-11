<?php
declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\Comment;
use App\Model\Repository\CommentRepository;

class CommentManager
{
    private $commentRepo;

    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }
    
    public function getSingleComment(int $commentId): ?Comment
    {
        $data = $this->commentRepo->getComment($commentId);

        return $data;
    }

    public function getComments(int $postId)
    {
        $data = $this->commentRepo->getAllComments($postId);

        return $data;
    }
}
