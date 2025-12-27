<?php


namespace App\Models;

use App\Core\Models;
use PDO;

class Posts extends Models
{
    protected string $tabela = 'posts';

    public function pesquisar(?string $text): array
    {
        $sql = "SELECT p.*, c.title AS category_title 
        FROM posts p 
        INNER JOIN category c ON p.id_categoria = c.id 
        WHERE p.posts LIKE '%$text%' 
        OR c.title LIKE '%{$text}%'";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    /*public function pesquisar(?string $text): array
    {
        $sql = "SELECT p.*, c.title AS category_title FROM posts p
         INNER JOIN category c ON p.id_categoria = c.id
         WHERE p.posts LIKE :text  OR p.posts LIKE :text  OR c.title LIKE :text";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':text', '%' . $text . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }*/
}
