<?php

namespace App\Models;

use App\Core\Models;
use PDO;

class Clubes extends Models
{
    protected string $table = "clubes";


    public function findClubes(string $terms = ""): ?array
    {        
        $query = "SELECT c.id, c.name, c.data_titulo, c.cores, c.title, c.estadio, c.status, ci.name AS city, 
                         t.imagem_time AS imagem FROM clubes c INNER JOIN city ci ON
                         ci.id = c.city LEFT JOIN times t ON t.id_club = c.id
                         GROUP BY c.id, c.name, c.data_titulo, c.cores, c.title, c.estadio, c.status, ci.name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function deleteClub(int $id)
    {
        $stmt = "DELETE FROM clubes WHERE {$id}";
        $stmt = $this->conn->prepare($stmt);
        $stmt->execute();
    }
}
