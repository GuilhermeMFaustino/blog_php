<?php


namespace App\Models;

use App\Core\Models;
use PDO;
use PDOException;

class Category extends Models
{
    protected string $table = 'category';


    public function findByCategory(?string $terms = null, string $columns = "*"): array
    {
        $sql = "SELECT p.*, c.title AS categoria FROM posts p LEFT JOIN category c ON c.id = p.id_categoria {$terms}";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


   /* public function save(string $table, array $dados): bool
    {

        try{
            
        }catch(PDOException){

        }
        $query = "INSERT INTO {$table} (title, text, status) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$dados['title'], $dados['text'], $dados['status']]);
    }*/

    /*public function update(string $table, int $id, array $dados): bool
    {

        $stmt = "UPDATE {$table} SET title = :title, text = :text, status = :status WHERE id = {$id}";
        $stmt = $this->conn->prepare($stmt);
        return $stmt->execute($dados);
    }*/


    public function delet(string $table, int $id): bool
    {
        $stmt = "DELETE FROM {$table} WHERE id = {$id}";
        $stmt = $this->conn->prepare($stmt);
        return $stmt->execute();
    }
}
