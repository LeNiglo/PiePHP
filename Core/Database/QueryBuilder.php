<?php

namespace Core\Database;

use \Core\Database\Database;
use \Core\Logger as Log;

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
    private $_offset = NULL;

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
            $sql .= " WHERE 1 " . $this->formatConditions();
        }

        var_dump($this, $sql);

        $query = $this->_db->prepare($sql);

        foreach ($this->_bindings as $key => $value) {
            $query->bindValue($key, $value);
        }

        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function formatConditions()
    {
        $sql = "";
        foreach ($this->_where as $value) {
            if (isset($value['cb'])) {
                $sql .= " {$value['link']} " . $this->getClosure($value['cb']);
            } else {
                $sql .= " {$value['link']} {$value['column']} {$value['op']} {$value['value']}";
            }
        }

        // if in a closure, remove the first logical keyword
        if (is_null($this->_table)) {
            $sql = preg_replace('/^\s*(AND|OR)/', '', $sql);
        }

        // return a string corresponding the the conditions in the $_where
        return ltrim($sql);
    }

    private function getClosure($cb)
    {
        $self = $this;
        // when using a user defined $cb function, create a new instance of
        // QueryBuilder and set $_table as NULL.
        $qb = $cb(new QueryBuilder(NULL));
        // when the $cb is returned, get the corresponding SQL
        $sql = $qb->formatConditions();

        // finally, append the bindings to the parent instance
        $bindings = $qb->getBindings();
        $sql = preg_replace_callback("/:b_(\d+)/", function ($matches) use ($self, $bindings) {
            return $self->addBinding($bindings[$matches[0]]);
        }, $sql);

        return '(' . ltrim($sql) . ')';
    }

    public function where($column, $op = NULL, $value = NULL)
    {
        return $this->stdWhere('AND', $column, $op, $value);
    }

    public function orWhere($column, $op = NULL, $value = NULL)
    {
        return $this->stdWhere('OR', $column, $op, $value);
    }

    public function whereIn($column, $values)
    {
        if (is_array($values)) {
            foreach ($values as &$value) {
                $value = $this->addBinding($value);
                unset($value);
            }
            $arrayStr = '(' . implode($values, ', ') . ')';
            return $this->stdWhere('AND', $column, 'IN', $arrayStr, true);
        }

        return $this;
    }

    public function whereBetween($column, $value1, $value2)
    {
        $betweenStr = $this->addBinding($value1) . ' AND ' . $this->addBinding($value2);
        return $this->stdWhere('AND', $column, 'BETWEEN', $betweenStr, true);
    }

    private function stdWhere($logicKeyword, $column, $op = NULL, $value = NULL, $bound = false)
    {
        if (!is_callable($column) && is_null($op)) {
            // if first param is not a callable, we need AT LEAST 2 of them
            throw new \InvalidArgumentException("Missing Parameter");
        } else if (is_callable($column)) {
            // if first param is a callable, add the function to the $_where
            $this->_where[] = [
                'link' => $logicKeyword,
                'cb' => $column,
            ];
        } else {
            // else, it is a basic where query
            if (is_null($value)) {
                // if the value is not defined, we use the "=" operator
                $value = $op;
                $op = '=';
            }

            // don't forget to add the binding !
            $this->_where[] = [
                'link' => $logicKeyword,
                'column' => $column,
                'op' => $op,
                'value' => $bound ? $value : $this->addBinding($value),
            ];
        }

        return $this;
    }

    private function addBinding($value)
    {
        $key = ":b_" . count($this->_bindings);
        $this->_bindings[$key] = $value;
        return $key;
    }

    public function getBindings()
    {
        return $this->_bindings;
    }
}
