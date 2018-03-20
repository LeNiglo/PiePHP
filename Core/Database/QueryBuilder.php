<?php

namespace Core\Database;

use \Core\Database\Database;

/**
 *
 */
class QueryBuilder
{

    private $_db;
    private $_table = NULL;
    private $_where = [];
    private $_select = '*';
    private $_bindings = [];
    private $_orderBy = [];
    private $_groupBy = [];
    private $_limit = NULL;
    private $_offset = NUL;

    function __construct($table)
    {
        $this->_db = Database::getInstance();
        $this->_table = $table;
    }

    public static function table($table)
    {
        return new QueryBuilder($table);
    }

    public function get()
    {
        if (is_array($this->_select)) {
            $select = implode(', ', $this->_select);
        } else {
            $select = $this->_select;
        }
        $sql = "SELECT {$select} FROM {$this->_table}";
        if (count($this->_where) > 0) {
            $sql .= " WHERE 1";
            foreach ($this->_where as $value) {
                $sql .= " AND {$value['column']} {$value['op']} {$value['value']}";
            }
        }


        var_dump($this, $sql);
        // return $sql;

        $query = $this->_db->prepare($sql);

        foreach ($this->_bindings as $key => $value) {
            $query->bindValue($key, $value);
        }

        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function where($column, $op, $value = NULL)
    {
        if (is_null($value)) {
            $value = $op;
            $op = '=';
        }

        $this->_where[] = [
            'column' => $column,
            'op' => $op,
            'value' => $this->addBinding($value),
        ];

        return $this;
    }

    public function whereIn($column, $values)
    {

    }

    public function whereBetween($column, $value1, $value2)
    {

    }

    private function addBinding($value)
    {
        $key = ":b_" . count($this->_bindings);
        $this->_bindings[$key] = $value;
        return $key;
    }
}
