<?php


namespace App\Core;

use App\Core\Connect;
use PDO;

abstract class Models
{
    public $conn;

    protected $tabela;
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

    public function setColumns( $columns): void
    {
        $this->columns = $columns;
    }

    public function setCond( $cond): void
    {
        $this->cond = $cond;
    }

    public function setQuery($query)
    {
            $this->query = $query;
    }

    public function find(string $columns = "*") 
    {
        $stmt = $this->conn->query("SELECT {$columns} FROM {$this->tabela}");        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function findByid(string $id, $terms = null, string $columns = "*")
    {
        $stmt = $this->conn->query("SELECT {$columns} FROM {$this->tabela} WHERE id = {$id} {$terms}");
        $findId = $stmt->fetchAll(PDO::FETCH_ASSOC);        
        if($stmt->rowCount() >= 1){
            return $findId;
        }else{
            return false;
        }
    }
}
