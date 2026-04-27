<?php 

namespace App\Models;

use App\Core\Models;
use PDO;

class Cidades extends Models
{
    protected string $table = "city";

    

    public function findByidCity(int $id, string $columns = "*"): ?array
    {
        $stmt = "SELECT {$columns} FROM {$this->table} WHERE id = {$id}";
        $stmt = $this->conn->prepare($stmt);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        
         return $result ?: null;
    }
}