<?php 

namespace BlahteSoftware\BsPaypal;

use BlahteSoftware\BsPaypal\Contracts\DatabaseInterface;
use PDO;
use PDOException;
use PDOStatement;
use stdClass;

final class Database implements DatabaseInterface {
    /**
     * @var \BlahteSoftware\BsPaypal\Database
     */
    protected static $instance;
    
    public PDO $pdo;

    private function __construct($host, $port, $engine, $name, $username, $password, $charset)
    {
        $dsn = "{$engine}:host={$host};dbname={$name};port={$port};charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch(PDOException $e) {
            throw $e;
        }
    }

    private function __clone()
    {
        // code
    }

    function getPdo(): PDO
    {
        return $this->pdo;
    }

    static function getInstance(
        string $DB_HOST = 'DB_HOST',
        string $DB_PORT = 'DB_PORT',
        string $DB_DRIVER = 'DB_DRIVER',
        string $DB_DATABASE = 'DB_DATABASE',
        string $DB_USERNAME = 'DB_USERNAME',
        string $DB_PASSWORD = 'DB_PASSWORD',
        string $DB_CHARSET = 'DB_CHARSET'
    ) : DatabaseInterface {
        if(is_null(self::$instance)) {
            if(function_exists('config')) {
                self::$instance = new self(
                    config('bspaypal.db.host'),
                    config('bspaypal.db.port'),
                    config('bspaypal.db.driver'),
                    config('bspaypal.db.database'),
                    config('bspaypal.db.username'),
                    config('bspaypal.db.password'),
                    config('bspaypal.db.charset')
                );
            } else {
                self::$instance = new self(
                    env($DB_HOST),
                    env($DB_PORT),
                    env($DB_DRIVER),
                    env($DB_DATABASE),
                    env($DB_USERNAME),
                    env($DB_PASSWORD),
                    env($DB_CHARSET),
                );
            }
        }

        return self::$instance;
    }

    static function setAttribute($name, $value) {
        self::getInstance()->pdo->setAttribute($name, $value);
    }

    static function insert($query, array $data) : int {        
        self::getInstance()->pdo->prepare($query)->execute($data);     
        return self::getInstance()->pdo->lastInsertId();
    }

    static function update($query, array $data) : int {
        $stmt = self::executeQuery($query,$data);
        return $stmt->rowCount();       
    }

    static function delete($query, array $data = null) : int {
        $stmt = self::executeQuery($query,$data);
        return $stmt->rowCount();       
    }

    static function findOne($query, array $data = null) {        
        $stmt = self::executeQuery($query,$data);          
        return $stmt->fetchObject();
    }

    static function findMany($query, array $data = null) : array {
        $stmt = self::executeQuery($query,$data);
        return($stmt->fetchAll(PDO::FETCH_OBJ));
    }

    static function executeQuery($query,$data = null) : PDOStatement {
        $stmt = self::getInstance()->pdo->prepare($query);
        $stmt->execute($data);
        return $stmt;
    }

    static function run($sql, $args = null) : PDOStatement {
        return self::executeQuery($sql, $args);
    }
} 