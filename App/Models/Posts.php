<?php


namespace App\Models;

use App\Core\Models;
use PDO;

class Posts extends Models
{
    protected string $table = 'posts';

    public function pesquisar(?string $text): array
    {
        $sql = "SELECT * FROM posts JOIN category ON posts.id_categoria = category.id WHERE posts.posts LIKE :text
                OR category.title LIKE :text";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':text', '%' . $text . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        
}
