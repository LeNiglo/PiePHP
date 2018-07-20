<?php

namespace Core;

use \Core\Database\ORM;
use \Core\Database\QueryBuilder;

/**
*
*/
class Entity
{
    protected static $_table = NULL;
    protected static $_id = 'id';
    protected $_fields = [];
    private $_relationships = [];
    private $_properties = [];
    private $_original = [];
    private $_dirty = [];

    public function __construct($params = [])
    {
        if (is_null(static::$_table)) {
            $this->_table = static::guessTable();
        }
        $this->set($params);
    }

    public static function guessTable()
    {
        return strtolower(str_replace('Model', '', basename(str_replace('\\', '/', get_called_class())))) . 's';
    }

    public static function getTable()
    {
        return static::$_table ?? static::guessTable();
    }

    public static function guessId()
    {
        return strtolower(str_replace('Model', '', basename(str_replace('\\', '/', get_called_class())))) . '_id';
    }

    public function getId()
    {
        return static::$_id ?? self::$_id;
    }

    public function getProperties()
    {
        return $this->_properties;
    }

    public function getOriginal()
    {
        return $this->_original;
    }

    public final function &__get($property)
    {
        return $this->get($property);
    }

    public final function __set($property, $value)
    {
        $this->set($property, $value);
    }

    public static function query()
    {
        return new QueryBuilder(self::getTable());
    }

    public final function &get($property)
    {
        $value = NULL;
        if (method_exists($this, $property)) {
            if (!array_key_exists($property, $this->_relationships)) {
                $this->_relationships[$property] = $this->$property();
            }
            $value =& $this->_relationships[$property];
        } elseif (array_key_exists($property, $this->_properties)) {
            $value =& $this->_properties[$property];
        }
        return $value;
    }

    public function set($property, $value = null)
    {
        if (is_string($property) && $property !== '') {
            $property = [$property => $value];
        }

        // Use correct types :)
        foreach ($property as &$value) {
            if ($value == "NULL") {
                $value = NULL;
            } elseif (is_numeric($value)) {
                $value += 0;
            } elseif (($tmp = \DateTime::createFromFormat('Y-m-d G:i:s', $value)) !== false) {
                $value = $tmp;
            } elseif (is_bool($value)) {
                settype($value, 'bool');
            }
            unset($value);
        }

        foreach ($property as $p => $value) {
            if (
                array_key_exists($p, $this->_properties) &&
                $this->_properties[$p] !== $value
            ) {
                $this->_dirty[$p] = true;
                $this->_properties[$p] = $value;
            } else if (
                in_array($p, $this->_fields) ||
                $p == static::getId()
            ) {
                $this->_original[$p] = $value;
                $this->_properties[$p] = $value;
            }
        }
    }

    public function save()
    {
        if (!isset($this->_original[static::getId()])) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    public function insert()
    {
        $values = [];

        foreach ($this->_dirty as $p => $is_dirty) {
            if ($is_dirty) {
                $values[$p] = $this->_properties[$p];
            }
        }
        $this->_dirty = [];
        return ORM::getInstance()->insert(static::getTable(), $values);
    }

    public function update()
    {
        $values = [];
        foreach ($this->_dirty as $p => $is_dirty) {
            if ($is_dirty) {
                $values[$p] = $this->_properties[$p];
            }
        }
        $this->_dirty = [];
        return ORM::getInstance()->update(static::getTable(), [static::getId() => $this->_original[static::getId()]], $values);
    }

    public function delete()
    {
        return ORM::getInstance()->delete(static::getTable(), [static::getId() => $this->_original[static::getId()]]);
    }

    public static function find($id)
    {
        $class = get_called_class();
        $results = ORM::getInstance()->find(static::getTable(), [static::getId() => $id]);

        if (!!$results && count($results) > 0) {
            return new $class($results[0]);
        } else {
            return NULL;
        }
    }

    public static function findAll($conditions = [])
    {
        $class = get_called_class();
        $results = ORM::getInstance()->find(static::getTable());

        if (!!$results && count($results) > 0) {
            return array_map(function ($entity) use ($class) {
                return new $class($entity);
            }, $results);
        } else {
            return [];
        }
    }

    protected final function hasManyThrough($class, $pivotClass, $fk1 = NULL)
    {
        if (!class_exists($class) || !is_subclass_of($class, '\Core\Entity')) {
            throw new \Exception("Class $class does not exists or isn't a \Core\Entity.");
        }
        if (!class_exists($pivotClass) || !is_subclass_of($pivotClass, '\Core\Entity')) {
            throw new \Exception("Class $pivotClass does not exists or isn't a \Core\Entity.");
        }
        if (is_null($fk1)) {
            $fk1 = $pivotClass::getId();
        }

        $pivotArray = ORM::getInstance()->find($pivotClass::getTable(), [static::guessId() => $this->{static::getId()}]);
        $pivotArray = array_map(function ($a) use ($fk1) {
            return $a[$fk1];
        }, $pivotArray);

        $array = Orm::getInstance()->findIn($class::getTable(), $class::getId(), $pivotArray);
        if (!!$array && count($array) > 0) {
            return array_map(function ($entity) use ($class) {
                return new $class($entity);
            }, $array);
        } else {
            return [];
        }
    }

    protected final function hasMany($class, $fk = NULL)
    {
        if (!class_exists($class) || !is_subclass_of($class, '\Core\Entity')) {
            throw new \Exception("Class $class does not exists or isn't a \Core\Entity.");
        }
        if (is_null($fk)) {
            $fk = $class::guessId();
        }

        return $class::findAll([$fk, $this->{static::getId()}]);
    }

    protected final function hasOne($class, $fk = NULL)
    {
        if (!class_exists($class) || !is_subclass_of($class, '\Core\Entity')) {
            throw new \Exception("Class $class does not exists or isn't a \Core\Entity.");
        }
        if (is_null($fk)) {
            $fk = $class::guessId();
        }

        $array = $class::findAll([$fk, $this->{static::getId()}]);
        return $array[0] ?? NULL;
    }

    protected final function belongsToMany($class, $pivotTable, $fk = NULL)
    {
        if (!class_exists($class) || !is_subclass_of($class, '\Core\Entity')) {
            throw new \Exception("Class $class does not exists or isn't a \Core\Entity.");
        }
        if (is_null($fk)) {
            $fk = $class::guessId();
        }

        $pivotArray = ORM::getInstance()->find($pivotTable, [static::guessId() => $this->{static::getId()}]);
        $pivotArray = array_map(function ($a) use ($fk) {
            return $a[$fk];
        }, $pivotArray);

        $array = Orm::getInstance()->findIn($class::getTable(), $class::getId(), $pivotArray);
        if (!!$array && count($array) > 0) {
            return array_map(function ($entity) use ($class) {
                return new $class($entity);
            }, $array);
        } else {
            return [];
        }
    }

    protected final function belongsTo($class, $fk = NULL)
    {
        if (!class_exists($class) || !is_subclass_of($class, '\Core\Entity')) {
            throw new \Exception("Class $class does not exists or isn't a \Core\Entity.");
        }
        if (is_null($fk)) {
            $fk = $class::guessId();
        }

        $array = $class::findAll([$fk, $this->{$class::getId()}]);
        return $array[0] ?? NULL;
    }
}
