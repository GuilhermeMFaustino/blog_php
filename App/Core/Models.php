<?php


namespace App\Core;

use App\Core\Connect;
use PDO;

abstract class Models
{
    public $conn;

    protected string $tabela;
    private $columns;

    private $query;
    private $cond;

    public function __construct()
    {
        $this->conn = Connect::getInstance();
    }

    public function getTabela(): mixed
    {
        return $this->tabela;
    }

    public function getColumns(): mixed
    {
        return $this->columns;
    }

    public function getCond(): mixed
    {
        return $this->cond;
    }

    public function setTabela($tabela): void
    {
        $this->tabela = $tabela;
    }

    public function setColumns($columns): void
    {
        $this->columns = $columns;
    }

    public function setCond($cond): void
    {
        $this->cond = $cond;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function find(string $columns = "*")
    {
        $stmt = ("SELECT {$columns} FROM {$this->tabela}");
        $stmt = $this->conn->prepare($stmt);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function findByid(int $id, $terms = null, string $columns = "*"): array|bool|object
    {
        $stmt = "SELECT {$columns} FROM {$this->tabela} WHERE id = {$id} {$terms}";
        $stmt = $this->conn->prepare($stmt);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_OBJ);
        if ($resultado) {
            return $resultado;
        } else {
            return false;
        }
    }


    

}
