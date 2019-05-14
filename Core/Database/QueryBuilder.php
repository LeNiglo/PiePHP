<?php

namespace Core\Database;

use Core\Entity;

class QueryBuilder
{
    private $_db;
    private $_table;
    private $_where = [];
    private $_select = '*';
    private $_bindings = [];
    private $_orderBy = [];
    private $_groupBy = [];
    private $_limit;
    private $_offset;

    public function __construct($table)
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
            $sql .= ' WHERE 1 ' . $this->formatConditions();
        }

        if (count($this->_orderBy) > 0) {
            $sql .= ' ORDER BY ' . implode(
                ', ', array_map(
                    function ($o) {
                        return "{$o['column']} {$o['order']}";
                    }, $this->_orderBy
                )
            );
        }

        if (!is_null($this->_limit)) {
            $sql .= ' LIMIT ';
            if (!is_null($this->_offset)) {
                $sql .= "{$this->_offset}, ";
            }
            $sql .= "{$this->_limit}";
        }

        \Log::debug($this);
        \Log::debug($sql);

        $query = $this->_db->prepare($sql);

        foreach ($this->_bindings as $key => $value) {
            $query->bindValue($key, $value);
        }

        $query->execute();
        if (!is_null($class = Entity::guessEntity($this->_table))) {
            return $query->fetchAll(\PDO::FETCH_CLASS, $class);
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function first()
    {
        return $this->get()[0] ?? null;
    }

    public function where($column, $op = null, $value = null)
    {
        return $this->stdWhere('AND', $column, $op, $value);
    }

    public function orWhere($column, $op = null, $value = null)
    {
        return $this->stdWhere('OR', $column, $op, $value);
    }

    public function whereIn($column, $values)
    {
        if (is_array($values)) {
            array_map(
                function ($value) {
                    return $this->addBinding($value);
                }, $values
            );

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

    public function orderBy($column, $order = 'ASC')
    {
        $this->_orderBy[] = [
            'column' => $column,
            'order' => $order,
        ];

        return $this;
    }

    public function limit($limit, $offset = null)
    {
        $this->_limit = $limit;
        $this->_offset = $offset;

        return $this;
    }

    public function getBindings()
    {
        return $this->_bindings;
    }

    private function formatConditions()
    {
        $sql = '';
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
        $qb = $cb(new QueryBuilder(null));
        // when the $cb is returned, get the corresponding SQL
        $sql = $qb->formatConditions();

        // finally, append the bindings to the parent instance
        $bindings = $qb->getBindings();
        $sql = preg_replace_callback(
            '/:b_(\\d+)/', function ($matches) use ($self, $bindings) {
                return $self->addBinding($bindings[$matches[0]]);
            }, $sql
        );

        return '(' . ltrim($sql) . ')';
    }

    private function stdWhere($logicKeyword, $column, $op = null, $value = null, $bound = false)
    {
        if (!is_callable($column) && is_null($op)) {
            // if first param is not a callable, we need AT LEAST 2 of them
            throw new \InvalidArgumentException('Missing Parameter');
        }
        if (is_callable($column)) {
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
        $key = ':b_' . count($this->_bindings);
        $this->_bindings[$key] = $value;

        return $key;
    }
}
