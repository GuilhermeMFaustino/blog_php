<?php


namespace App\Core;

use App\Core\Connect;
use PDO;
use PDOException;

abstract class Models
{
    public $conn;

    protected string $table;

    protected string $order;
    protected string $limit;
    protected string $offset;
    protected string $error;

    private $columns;

    private $query;
    private $cond;

    public function __construct()
    {
        $this->conn = Connect::getInstance();
    }

    public function getTabela(): mixed
    {
        return $this->table;
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
        $this->table = $tabela;
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
        $stmt = ("SELECT {$columns} FROM {$this->table}");
        $stmt .= $this->order ?? '';
        $stmt .= $this->limit ?? '';
        $stmt .= $this->offset ?? '';
        $stmt = $this->conn->prepare($stmt);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function save(string $table, array $dados): bool|string|null
    {

        try {
            $colums = implode(', ', array_keys($dados));
            $values = ':' . implode(',:', array_keys($dados));
            $query = "INSET INTO {$this->table} {$colums} VALUES {$values}";
            $stmt = Connect::getInstance()->prepare($query);
            $stmt->execute($this->filter($dados));

            return Connect::getInstance()->lastInsertId();
        } catch (PDOException $ex) {
            echo $this->error = $ex;
            return null;
        }
    }

    public function update(array $dados, string $termos): ?int
    {
        try {
            $set = [];
            foreach ($dados as $key => $value) {
                $set[] = "{$key} = :{$key}";
            }
            $set = implode(', ', $set);
            $sql = "UPDATE {$this->table} SET {$set} WHERE {$termos}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($dados);
            return $stmt->rowCount();
        } catch (PDOException $ex) {
            $this->error = $ex->getMessage();
            return null;
        }
    }

    public function delete(string $id)
    {
        try {

            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare(
                "DELETE FROM posts WHERE id_categoria = :id"
            );
            $stmt->execute(['id' => $id]);

            $stmt = $this->conn->prepare(
                "DELETE FROM category WHERE id = :id"
            );
            $stmt->execute(['id' => $id]);

            $this->conn->commit();
        } catch (PDOException $ex) {
            echo $this->error = $ex;
        }
    }



    public function findByid(int $id, $terms = null, string $columns = "*"): array|bool|object
    {
        $stmt = "SELECT {$columns} FROM {$this->table} WHERE id = {$id} {$terms}";
        $stmt = $this->conn->prepare($stmt);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_OBJ);
        if ($resultado) {
            return $resultado;
        } else {
            return false;
        }
    }

    public function order(string $order): ?object
    {
        $this->order = " ORDER BY {$order}";
        return $this;
    }

    public function limit(string $limit): ?object
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    public function offset(string $offset): ?object
    {
        $this->offset = " OFFSET BY {$offset}";
        return $this;
    }

    private function filter(array $dados)
    {
        $filtro = [];

        foreach ($dados as $chave => $valor) {
            $filtro[$chave] = (is_null($valor) ? null : filter_var($valor, FILTER_DEFAULT));
        }
    }

    /*public function result(bool $dados = false)
    {
        try {
            $stmt = Connect::getInstance()->prepare($this->query);
            $stmt->execute($this->params);

            if (!$stmt->rowCount()) {
                return null;
            }

            if ($dados) {
                return $stmt->fetchAll();
            }

            return $stmt->fetch();
        } catch (PDOException $ex) {
            $this->erro = $ex;
        }
    }*/
}
