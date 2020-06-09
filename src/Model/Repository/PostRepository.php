<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Post;
use App\Service\Database;

class PostRepository extends Database
{
    public function getPost(int $postId): Post
    {
        $result = $this->dbConnect()->prepare(
            'SELECT post.id AS postId, post.title, post.chapo, post.content, DATE_FORMAT(post.created, \'%d/%m/%Y à %Hh%imin%ss\') AS created, DATE_FORMAT(post.last_update, \'%d/%m/%Y à %Hh%imin%ss\') AS lastUpdate, post.user_id AS authorId, user.login AS authorLogin 
        FROM post 
        INNER JOIN user ON post.user_id = user.id
        WHERE post.id= :postId'
        );
        
        $result->execute([':postId' => $postId]);
        $data = $result->fetch(\PDO::FETCH_ASSOC);

        return new \App\Model\Entity\Post($data);
    }
}
