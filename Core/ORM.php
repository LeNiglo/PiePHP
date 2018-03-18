<?php

namespace Core;

use \Core\Database;

/**
*
*/
class ORM
{
    private $db;
    private static $_instance = NULL;

    function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new ORM();
        }
        return self::$_instance;
    }

    public function insert($table, $fields = [])
    {
        if (count($fields) > 0) {
            $sql = "INSERT INTO $table VALUES ";
            $sql .= "(" . implode(', ', array_keys($fields)) .") " . implode(', ', array_map(function ($p) {
                return ":$p";
            }, array_keys($fields)));
            $query = $this->db->prepare($sql);

            foreach ($fields as $p => $v) {
                $query->bindValue(":$p", $v);
            }

            return $query->execute();
        } else {
            return NULL;
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
        $query = $this->db->prepare($sql);

        foreach ($fields as $p => $v) {
            $query->bindValue(":$p", $v);
        }
        $this->bindCondition($query, $condition);

        $query->execute();
    }

    public function delete($table, $condition = [])
    {
        $sql = "DELETE FROM $table WHERE 1" . $this->formatCondition($condition);
        $query = $this->db->prepare($sql);
        $this->bindCondition($query, $condition);
        return $query->execute();
    }

    public function find($table, $condition = [])
    {
        $sql = "SELECT * FROM  $table WHERE 1" . $this->formatCondition($condition);
        $query = $this->db->prepare($sql);
        $this->bindCondition($query, $condition);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findIn($table, $value, $array) {
        $sql = "SELECT * FROM $table WHERE $value IN (" . implode(', ', array_map(function ($p) {
            return ":cond_$p";
        }, array_keys($array))) . ")";
        $query = $this->db->prepare($sql);
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
