<?php

namespace App\Models;

use App\Core\Models;
use PDO;

class Jogos extends Models
{

    protected string $table = "jogos";


    public function serarchTimeCity()
    {
        $sql = "SELECT j.id,  t1.time AS nome_time_um, t2.time AS nome_time_dois, c1.name AS cidade_time_um, c2.name AS cidade_time_dois,
                                                        j.hora,
                                                        j.rodada,
                                                        j.status
                    FROM jogos j
                    LEFT JOIN times t1 ON t1.id = j.timeum
                    LEFT JOIN times t2 ON t2.id = j.timedois
                    LEFT JOIN city c1 ON c1.id = j.cityUm
                    LEFT JOIN city c2 ON c2.id = j.cityDois";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);       
        return $result ?: null;
    }

    public function serarchJogoCity($id)
    {
        $sql = "SELECT j.id,  t1.time AS nome_time_um, t2.time AS nome_time_dois, c1.name AS cidade_time_um, c2.name AS cidade_time_dois,
                                                        j.hora,
                                                        j.rodada,
                                                        j.status
                    FROM jogos j
                    LEFT JOIN times t1 ON t1.id = j.timeum
                    LEFT JOIN times t2 ON t2.id = j.timedois
                    LEFT JOIN city c1 ON c1.id = j.cityUm
                    LEFT JOIN city c2 ON c2.id = j.cityDois WHERE j.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);       
        return $result ?: null;
    }
}
