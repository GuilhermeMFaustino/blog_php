<?php


namespace App\Models;

use App\Core\Models;
use PDO;

class Category extends Models
{
    protected string $category = 'category';


    
    public function findByCategory(?string $terms = null, string $columns = "*"): array
    {
        $sql = "SELECT p.*, c.title AS categoria FROM posts p LEFT JOIN category c ON c.id = p.id_categoria {$terms}";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
