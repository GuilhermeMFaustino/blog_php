<?php 


namespace App\Models;

use App\Core\Models;
use PDO;

class Posts extends Models
{
    protected string $tabela = 'posts';


    public function search(?string $text, ?string $terms = null): array
    {
        $terms = ($terms ? "{$terms}" : '');

        //$sql = "SELECT * FROM category WHERE posts LIKE '%{$text}%' OR title LIKE '%{$text}%'";
        $sql = "SELECT * FROM posts {$terms}";
        $stmt = $this->conn->query($sql);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
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

    public function save(string $table, array $dados): bool
    {

        $query = "INSERT INTO {$table} (id_categoria, posts, text, status) VALUES (:id_categoria, :posts, :text, :status)";
         $stmt = $this->conn->prepare($query);
        return $stmt->execute($dados);
    }

     public function update(string $table, int $id, array $dados): bool
    {

            $stmt = "UPDATE {$table} SET id_categoria = :id_categoria, posts = :posts, text = :text, status = :status WHERE id = {$id}";
            $stmt = $this->conn->prepare($stmt);
           return $stmt->execute($dados);

    }

    public function delet(string $table, int $id): bool
    {
        $stmt = "DELETE FROM {$table} WHERE id={$id}";
        $stmt = $this->conn->prepare($stmt);
        return $stmt->execute();
    }

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