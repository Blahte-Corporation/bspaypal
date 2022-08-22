<?php

namespace BlahteSoftware\BsPaypal\Utils;

use Exception;
use PDO;
use PDOException;

if(! function_exists('findObjectByPropertyValue') ) {
    function findObjectByPropertyValue(array $list, string $property, mixed $value, $default = null) {
        foreach($list as $obj) {
            if(! is_object($obj)) continue;
            if(! property_exists($obj, $property)) continue;
            if($obj->$property == $value) return $obj;
        }
        return $default;
    }
}

if(! function_exists('table_exists') ) 
{
    /**
     * Check if a table exists in the current database.
     *
     * @param PDO $pdo PDO instance connected to a database.
     * @param string $table Table to search for.
     * @return bool TRUE if table exists, FALSE if no table found.
     * 
     * Credit: https://stackoverflow.com/a/14355475/10633355
     */
    function table_exists(PDO $pdo, string $table) : bool
    {
        try {
            $result = $pdo->query("SELECT 1 FROM {$table} LIMIT 1");
            return $result !== FALSE;
        } catch (Exception $e) {
            return FALSE;
        }
    }
}

if(! function_exists('table_create') )
{
    /**
     * Use PDO instance to create a table with
     * the given SQL
     * 
     * @param PDO $pdo
     * @param string $sql
     * @return bool
     */
    function table_create(PDO $pdo, string $sql) : bool 
    {
        $stmt = $pdo->prepare($sql);
        if(! $stmt->execute()) {
            throw new PDOException("{$stmt->errorInfo()[0]}: {$stmt->errorInfo()[2]}");
        }
        return true;
    }
}

if(! function_exists('database_name') ) 
{
    /**
     * Retrieve name of the database associated with the pdo instance
     * 
     * @param PDO $pdo
     * @return string $databaseName
     */
    function database_name(PDO $pdo) : string 
    {
        return $pdo->query('select database()')->fetchColumn();
    }
}

if(! function_exists('column_exists') )
{
    /**
     * Checks whether a specified column exists on the specified table
     * 
     * @param PDO $pdo
     * @param string $tableName
     * @param string $columnName
     * @return bool
     */
    function column_exists(PDO $pdo, string $tableName, string $columnName) : bool 
    {
        return count($pdo->query("SHOW COLUMNS FROM `$tableName` LIKE '$columnName'")->fetchAll()) > 0;
    }
}

if(! function_exists('table_alter') )
{
    function table_alter(PDO $pdo, $sql)
    {
        $stmt = $pdo->prepare($sql);
        if(! $stmt->execute()) {
            throw new PDOException("{$stmt->errorInfo()[0]}: {$stmt->errorInfo()[2]}");
        }
        return true;
    }
}

if(! function_exists('get_insert_statement') ) 
{
    function get_insert_statement(string $tableName, array $databaseTableColumns, array $merge=[]) 
    {
        $databaseTableColumns = array_merge($databaseTableColumns, array_keys($merge));
        $columns = implode(",", $databaseTableColumns);
        $tokens = implode(",", array_reduce($databaseTableColumns, function($previous, $current) use ($merge) {
            if(in_array($current, array_keys($merge))) {
                $previous[] = "'{$merge[$current]}'";
            } else {
                $previous[] = ":{$current}";
            }
            return $previous;
        }, []));
        return "INSERT INTO `{$tableName}` ({$columns}) values ({$tokens})";
    }
}

if(! function_exists('table_insert') )
{
    function table_insert(PDO $pdo, string $tableName, array $requestData)
    {
        $columns = array_keys($requestData);
        $databaseTableColumns = array_reduce(table_columns($pdo, $tableName), function($previous, $current) use($columns) {
            if(in_array($current, $columns)) {
                $previous[] = $current;
            }
            return $previous;
        }, []);
        $sql = get_insert_statement($tableName, $databaseTableColumns, []);
        $data = array_reduce($databaseTableColumns, function($previous, $current) use ($requestData) {
                $previous[$current] = $requestData[$current];
                return $previous;
            }, []);      
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute($data);

        if(! $ok) {
            throw new PDOException("{$stmt->errorCode}: {$stmt->errorMessage}");
        }

        return true;
    }
}

if(! function_exists('table_columns') )
{
    /**
     * Retrieves the names of all columns in the given table as a 1D array
     * 
     * @param PDO $pdo
     * @param string $tableName
     */
    function table_columns(PDO $pdo, string $tableName) : array
    {
        $rs = $pdo->query("SELECT * FROM `{$tableName}` LIMIT 0");
        for ($i = 0; $i < $rs->columnCount(); $i++) {
            $col = $rs->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }
}

/*/
table_insert($this->db->pdo, $tableName, $this->params, $fields); 
$id = $this->db->pdo->lastInsertId();
$n = Database::findOne("SELECT * FROM $tableName WHERE id=:id", [
    'id' => $id
]);

$sql = 
<<<SQL
UPDATE `{$tableName}` SET verified_at=:verified_at WHERE id=:id
SQL;
$stmt = Database::run($sql, [
    'id' => $id,
    'verified_at' => timestamp('now', "Y-m-d H:i:s")
]);
/*/