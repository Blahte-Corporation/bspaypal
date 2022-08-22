<?php 

namespace BlahteSoftware\BsPaypal;

use PDO;
use PDOStatement;

abstract class DatabaseModel 
{
    const TABLE = '';

    private function __construct()
    {
        // code
    }

    static function where($column, $operator = '=', $value)
    {
        return Database::run("SELECT * FROM `".static::TABLE."` WHERE {$column}{$operator}:{$column}", [
            "$column" => $value
            ])->fetch(PDO::FETCH_OBJ);
    }

    static function find(int $id)
    {
        return Database::run("SELECT * FROM `".static::TABLE."` WHERE `id` = ?", [$id])->fetch();
    }

    static function all() : array
    {
        return Database::findMany("SELECT * FROM `".static::TABLE."`");
    }

    static function delete(int $id) : int
    {
        return Database::delete("DELETE FROM `".static::TABLE."` WHERE `id` = ?", [$id]);
    }

    static function deleteAll() : int
    {
        return Database::delete("DELETE FROM `".static::TABLE."`");
    }

    static function run($sql, $args = null)
    {
        return Database::run($sql, $args);
    }

    static function update($sql, array $data)
    {
        return Database::update($sql, $data);
    }
}