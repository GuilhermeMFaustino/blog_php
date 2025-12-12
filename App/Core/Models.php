<?php


namespace App\Core;

use App\Core\Connect;

class Models
{
    public $conn;

    private $tabela;
    private $columns;

    private $query;
    private $cond;

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

    public function __construct()
    {
       $this->conn = new Connect();
    }
    
    public function find(string $columns = "*") 
    {
        $stmt = Connect::getInstance()->query("SELECT {$columns} FROM {$this->getTabela()}");        
        $result = $stmt->fetchAll();
        var_dump($result);
    }
}
