<?php


namespace App\Models;

use App\Core\Models;
use PDO;

class Posts extends Models
{
    protected string $table = 'posts';


    /*public function search(?string $text): array
    {       
        $sql = "SELECT * FROM posts WHERE posts LIKE '%{$text}%' OR text LIKE '%{$text}%'";
        $stmt = $this->conn->query($sql);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        var_dump($resultado);
        return $resultado;
    }*/

    public function pesquisar(?string $text): array
    {
        $sql = "SELECT * FROM posts JOIN category ON posts.id_categoria = category.id WHERE posts.posts LIKE :text
                OR category.title LIKE :text";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':text', '%' . $text . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(string $table, array $dados): bool
    {

        $query = "INSERT INTO {$table} (id_categoria, posts, text, status) VALUES (:id_categoria, :posts, :text, :status)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($dados);
    }

    /*public function update(string $table, int $id, array $dados): bool
    {

        $stmt = "UPDATE {$table} SET id_categoria = :id_categoria, posts = :posts, text = :text, status = :status WHERE id = {$id}";
        $stmt = $this->conn->prepare($stmt);
        return $stmt->execute($dados);
    }*/

    /*public function delet(string $table, int $id): bool
    {
        $stmt = "DELETE FROM {$table} WHERE id={$id}";
        $stmt = $this->conn->prepare($stmt);
        return $stmt->execute();
    }*/

    public function total(?string $terms = null): int
    {
        $terms = ($terms ? "WHERE {$terms}" : '');
        $stmt = "SELECT * FROM posts {$terms}";
        $stmt = $this->conn->prepare($stmt);
        $stmt->execute();
        //var_dump($stmt);
        return $stmt->rowCount();
    }
}
