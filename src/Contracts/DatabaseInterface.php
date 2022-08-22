<?php

namespace BlahteSoftware\BsPaypal\Contracts;

use PDO;
use PDOStatement;

interface DatabaseInterface {
    function getPdo() : PDO;

    static function getInstance(
        string $DB_HOST = 'DB_HOST',
        string $DB_PORT = 'DB_PORT',
        string $DB_DRIVER = 'DB_DRIVER',
        string $DB_DATABASE = 'DB_DATABASE',
        string $DB_USERNAME = 'DB_USERNAME',
        string $DB_PASSWORD = 'DB_PASSWORD',
        string $DB_CHARSET = 'DB_CHARSET'
    ) : DatabaseInterface;

    static function run($sql, $args = null) : PDOStatement;

    static function findOne($query, array $data = null);

    static function insert($query, array $data) : int;

    static function update($query, array $data) : int;

    static function delete($query, array $data = null) : int;
}