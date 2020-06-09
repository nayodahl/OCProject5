<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Post;
use App\Service\Database;

class PostRepository extends Database
{
    public function getPost(int $postId): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT post.id, post.title, post.chapo, post.content, DATE_FORMAT(post.created, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr, DATE_FORMAT(post.last_update, \'%d/%m/%Y à %Hh%imin%ss\') AS update_date_fr, post.user_id, user.login 
        FROM post 
        INNER JOIN user ON post.user_id = user.id
        WHERE post.id= :postId'
        );
        
        $result->execute([':postId' => $postId]);
        $data = $result->fetch();

        return $data;
    }
    
    public function findById(int $id): array
    {
        return ['id' => $id, 'title' => 'Article '. $id .' du blog', 'text' => 'Lorem ipsum'];
    }
}
