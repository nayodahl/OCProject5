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

    public function getApprovedComments(int $postId): array
    {
        // second parameter is the status of the comment, 1 for approved
        $data = $this->commentRepo->getAllComments($postId, 1);

        return $data;
    }
}
