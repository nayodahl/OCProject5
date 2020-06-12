<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Post;
use App\Service\Database;

class PostRepository extends Database
{
    // get Post with its id
    // Return Post
    public function getPost(int $postId): Post
    {
        $result = $this->dbConnect()->prepare(
            'SELECT post.id AS postId, post.title, post.chapo, post.content, DATE_FORMAT(post.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(post.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, post.user_id AS authorId, user.login AS authorLogin 
            FROM post 
            INNER JOIN user ON post.user_id = user.id
            WHERE post.id= :postId'
        );
        $result->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $result->execute();
        $data = $result->fetch(\PDO::FETCH_ASSOC);

        return new \App\Model\Entity\Post($data);
    }
    // get last X Posts, sorted by most recent
    // return an array of Posts
    public function getMostXRecentPosts(int $postsNumberLimit): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT post.id AS postId, post.title, post.chapo, post.content, DATE_FORMAT(post.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(post.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, post.user_id AS authorId, user.login AS authorLogin 
            FROM post 
            INNER JOIN user ON post.user_id = user.id
            ORDER BY post.created DESC LIMIT :postsNumberLimit'
        );
        $result->bindValue(':postsNumberLimit', $postsNumberLimit, \PDO::PARAM_INT);
        $result->execute();
        $custom_array = [];

        while ($data = $result->fetch(\PDO::FETCH_ASSOC)) {
            array_push($custom_array, new Post($data));
        }
        return $custom_array;
    }

    // get all Posts, sorted by most recent
    // return an array of Posts
    public function getAllPosts(): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT post.id AS postId, post.title, post.chapo, post.content, DATE_FORMAT(post.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(post.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, post.user_id AS authorId, user.login AS authorLogin 
            FROM post 
            INNER JOIN user ON post.user_id = user.id
            ORDER BY post.created DESC '
        );
        $result->execute();
        $custom_array = [];

        while ($data = $result->fetch(\PDO::FETCH_ASSOC)) {
            array_push($custom_array, new Post($data));
        }
        return $custom_array;
    }

    // get total number of Posts
    // return an int
    public function CountPosts(): int
    {
        $result = $this->dbConnect()->prepare(
            'SELECT * FROM post'
        );
        $result->execute();
        $count = $result->rowCount();
        return $count;
    }
}
