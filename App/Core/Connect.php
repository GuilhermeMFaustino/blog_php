<?php 


namespace App\Core;

use PDO;
use PDOException;
use App\Source\Config;
use Exception;

class Connect
{

    private $conn;

    private static $instance;

    private const OPTIONS = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ];

    public static function getInstance(): ?PDO
    {        
        if(empty(self::$instance)){           

            try{
                self::$instance = new PDO(
                    "mysql:host=" . CONF_DB_HOST . ";dbname=" . CONF_DB_NAME,
                    CONF_DB_USER,
                    CONF_DB_PASS,
                    self::OPTIONS
                );
            }catch(PDOException $e){
                echo "Erro ao conectar com o Banco de Dados ". $e->getMessage();
                die();
            }
        }
        return self::$instance;
    }

    protected function __construct()
    {
        
    }

    private function __clone()
    {
       
    }
}