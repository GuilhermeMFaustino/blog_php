<?php


namespace App\Core;

use App\Core\Connect;
use App\Support\Menssage;
use PDO;
use PDOException;
use stdClass;

abstract class Models
{
    public $conn;

    protected string $table;

    protected string $order;
    protected string $limit;
    protected string $offset;
    protected string $error;
    protected $message;
    protected array $params;

    protected int $id;

    protected object $dados;
    private $columns;
    private $query;
    private $cond;

    public function __construct()
    {
        $this->conn = Connect::getInstance();
        $this->message = new Menssage();
    }

    /**
     * refatorado
     * @param mixed $terms
     * @param mixed $params
     * @param mixed $columns
     * @return array
     */

    /*public function find(?string $terms = null, ?string $params = null, string $columns = "*"): ?Models
    {
        if($terms){
            $this->query = "SELECT {$columns} FROM {$this->table} WHERE {$terms}";
            parse_str($params, $this->params);
            return $this;
        }
        $this->query = "SELECT {$columns} FROM {$this->table}";
        return $this;
    }*/

    public function find(?string $terms = null, ?string $params = null, ?string $columns = "*")
    {
        $sql = "SELECT {$columns} FROM {$this->table}";
        if ($params) {
            $sql .= " WHERE {$params}";
        }

        $sql .= $this->order ?? '';
        $sql .= $this->limit ?? '';
        $sql .= $this->offset ?? '';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }



    public function result(bool $dados = false)
    {
        try {
            $stmt = Connect::getInstance()->prepare($this->query . $this->order . $this->offset);
            $stmt->execute($this->params);

            if (!$stmt->rowCount()) {
                return null;
            }

            if ($dados) {
                return $stmt->fetchAll();
            }

            return $stmt->fetchObject(static::class);
        } catch (PDOException $ex) {
            $this->error = $ex;
        }

        return $this;
    }



    public function save(array $dados): bool|string|null
    {
        try {
            $colums = implode(', ', array_keys($dados));
            $values = ':' . implode(', :', array_keys($dados));
            $query = "INSERT INTO {$this->table} ({$colums}) VALUES ({$values})";
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
            return ($stmt->rowCount() ?? 1);
        } catch (PDOException $ex) {
            $this->error = $ex->getMessage();
            return null;
        }
    }

    public function delete(string $terms): bool|null
    {
        try {
            $query = "DELETE FROM {$this->table} WHERE {$terms}bcvbcvbc";
            $stmt = Connect::getInstance()->prepare($query);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            $this->error = $ex->getMessage();
            return false;
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


    
   public function findByEmail(string $email, string $terms = "", string $columns = "*"): ?object
{
    // Usamos coalescência para evitar erros caso $terms seja null
    $query = "SELECT {$columns} FROM {$this->table} WHERE email = :email " . ($terms ?: "");
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    // Retorna o objeto ou null se não encontrar nada
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    return $result ?: null;
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
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    public function error(): string
    {
        return $this->error;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function dados()
    {
        return $this->dados;
    }

    public function __set($name, $value)
    {
        if (empty($this->dados)) {
            $this->dados = new \stdClass();
        }
        $this->dados->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->dados);
    }

    private function filter(array $dados)
    {
        $filter = [];

        foreach ($dados as $chave => $valor) {
            $filter[$chave] = (is_null($valor) ? null : filter_var($valor, FILTER_DEFAULT));
        }

        return $filter;
    }


    protected function create()
    {
        $dados = (array) $this->dados;
        return $dados;
    }

    public function salvar()
    {
        /**Cadastrar */
        if (empty($this->id)) {
            $id = $this->save($this->create());
            if ($this->error) {
                $this->message->error("Erro de sistema ao tentar Cadastrar os dados");
                return false;
            }
        }
        /**
         * atualiza
         */

        if (!empty($this->id)) {
            $id = $this->id;
            $this->update($this->create(), "id={$id}");
            if ($this->error) {
                $this->message->error("Erro de sistema ao tentar Cadastrar os dados");
                return false;
            }
        }
        $this->dados = $this->findByid($id)->dados();
        return true;
    }

    public function total(?string $terms = null): int
    {
        $terms = ($terms ? "WHERE {$terms}" : '');
        $stmt = "SELECT * FROM posts {$terms}";
        $stmt = $this->conn->prepare($stmt);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
