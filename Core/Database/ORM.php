<?php

namespace Core\Database;

use \Core\Database\Database;

/**
*
*/
class ORM
{
    private $_db;
    private static $_instance = null;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new ORM();
        }
        return self::$_instance;
    }

    public function insert($table, $fields = [])
    {
        if (count($fields) > 0) {
            $sql = "INSERT INTO $table ";
            $sql .= "(" . implode(', ', array_keys($fields)) .") ";
            $sql .= "VALUES (" . implode(', ', array_map(function ($p) {
                return ":$p";
            }, array_keys($fields))) . ")";
            $query = $this->_db->prepare($sql);

            foreach ($fields as $p => $v) {
                $query->bindValue(":$p", $v);
            }

            $query->execute();
            return $this->_db->lastInsertId();
        } else {
            return null;
        }
    }

    public function update($table, $condition = [], $fields = [])
    {
        $sql = "UPDATE $table SET ";
        $first = true;

        foreach ($fields as $p => $v) {
            if ($first === false) {
                $sql .= ", ";
            } else {
                $first = false;
            }
            $sql .= "$p = :$p ";
        }


        $sql .= "WHERE 1" . $this->formatCondition($condition);
        $query = $this->_db->prepare($sql);

        foreach ($fields as $p => $v) {
            $query->bindValue(":$p", $v);
        }
        $this->bindCondition($query, $condition);

        $query->execute();
        return $query->rowCount();
    }

    public function delete($table, $condition = [])
    {
        $sql = "DELETE FROM $table WHERE 1" . $this->formatCondition($condition);
        $query = $this->_db->prepare($sql);
        $this->bindCondition($query, $condition);
        $query->execute();
        return $query->rowCount();
    }

    public function find($table, $condition = [])
    {
        $sql = "SELECT * FROM  $table WHERE 1" . $this->formatCondition($condition);
        $query = $this->_db->prepare($sql);
        $this->bindCondition($query, $condition);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findIn($table, $value, $array)
    {
        $sql = "SELECT * FROM $table WHERE $value IN ";
        $sql .= "(" . implode(', ', array_map(function ($p) {
            return ":cond_$p";
        }, array_keys($array))) . ")";
        $query = $this->_db->prepare($sql);
        $this->bindCondition($query, array_values($array));
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function formatCondition($condition = [])
    {
        $sql = "";
        foreach ($condition as $p => $v) {
            $sql .= " AND $p = :cond_$p";
        }
        return $sql;
    }

    private function bindCondition(&$query, $condition = [])
    {
        foreach ($condition as $p => $v) {
            $query->bindValue(":cond_$p", $v);
        }
    }
}
