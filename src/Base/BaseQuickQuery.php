<?php

namespace Fuz\Component\QuickQuery\Base;

use Fuz\Component\QuickQuery\Builder\BuilderInterface;
use Fuz\Component\QuickQuery\Driver\DriverInterface;

class BaseQuickQuery
{

    protected $driver;
    protected $builder;

    public function __construct(DriverInterface $driver, BuilderInterface $builder)
    {
        $this->driver = $driver;
        $this->builder = $builder;
    }

    public function asSingleRow($request)
    {
        $results = call_user_func_array(array ($this->driver, 'query'),
           array_merge(array ($request), array_slice(func_get_args(), 1)));
        return $this->getSingleRow($results);
    }

    protected function getSingleRow(array $results)
    {
        return count($results) > 0 ? $results[0] : null;
    }

    public function asSingleField($request, $field)
    {
        $results = call_user_func_array(array ($this->driver, 'query'),
           array_merge(array ($request), array_slice(func_get_args(), 2)));
        return $this->getSingleField($results, $field);
    }

    protected function getSingleField(array $results, $field)
    {
        return ((count($results) > 0) && (array_key_exists($field, $results[0]))) ? $results[0][$field] : null;
    }

    public function asArray($request)
    {
        return call_user_func_array(array ($this->driver, 'query'),
           array_merge(array ($request), array_slice(func_get_args(), 1)));
    }

    public function asArrayField($request, $field)
    {
        $results = call_user_func_array(array ($this->driver, 'query'),
           array_merge(array ($request), array_slice(func_get_args(), 2)));
        return $this->getArrayField($results, $field);
    }

    protected function getArrayField(array $results, $field)
    {
        $fields = array ();
        foreach ($results as $result)
        {
            if (array_key_exists($field, $result))
            {
                $fields[] = $result[$field];
            }
        }
        return $fields;
    }

    public function asAssociativeArray($request, $key)
    {
        $results = call_user_func_array(array ($this->driver, 'query'),
           array_merge(array ($request), array_slice(func_get_args(), 2)));
        return $this->getAssociativeArray($results, $key);
    }

    protected function getAssociativeArray(array $results, $key)
    {
        $array = array ();
        foreach ($results as $result)
        {
            if (array_key_exists($key, $result))
            {
                $array[$result[$key]] = $result;
            }
        }
        return $array;
    }

    public function asAssociativeArrayField($request, $key, $field)
    {
        $results = call_user_func_array(array ($this->driver, 'query'),
           array_merge(array ($request), array_slice(func_get_args(), 3)));
        return $this->getAssociativeArrayField($results, $key, $field);
    }

    protected function getAssociativeArrayField(array $results, $key, $field)
    {
        $array = array ();
        foreach ($results as $result)
        {
            if ((array_key_exists($key, $result)) && (array_key_exists($field, $result)))
            {
                $array[$result[$key]] = $result[$field];
            }
        }
        return $array;
    }

    public function select($table, array $wheres = array ())
    {
        $request = $this->builder->buildSelect($table, $wheres);
        return $this->driver->query($request);
    }

    public function count($table, array $wheres = array ())
    {
        $request = $this->builder->buildCount($table, $wheres);
        $results = $this->driver->query($request);
        return $this->getSingleField($results, 'count');
    }

    public function has($table, array $wheres = array ())
    {
        $request = $this->builder->buildHas($table, $wheres);
        $results = $this->driver->query($request);
        return count($results) > 0;
    }

    public function insert($table, array $columnsValues, $ignore = false)
    {
        $request = $this->builder->buildInsert($table, $columnsValues, $ignore);
        $this->driver->query($request);
        return $this->driver->insertId();
    }

    public function insertUpdate($table, array $columnsValues)
    {
        $request = $this->builder->buildInsertUpdate($table, $columnsValues);
        return $this->driver->query($request);
    }

    public function update($table, array $columnsValues, array $wheres = array ())
    {
        $request = $this->builder->buildUpdate($table, $columnsValues, $wheres);
        return $this->driver->query($request);
    }

    public function delete($table, array $wheres = array ())
    {
        $request = $this->builder->buildDelete($table, $wheres);
        return $this->driver->query($request);
    }

    public function truncate($table)
    {
        $request = $this->builder->buildTruncate($table);
        return $this->driver->query($request);
    }

    public function exists($table)
    {
        $request = $this->builder->buildExists($table);
        return count($this->driver->query($request)) > 0;
    }

    public function describe($table)
    {
        $request = $this->builder->buildDescribe($table);
        return $this->driver->query($request);
    }

    public function columns($table)
    {
        $request = $this->builder->buildDescribe($table);
        $results = $this->driver->query($request);
        return $this->getArrayField($results, $this->builder->getDescribeField());
    }

    public function emptyRow($table)
    {
        $request = $this->builder->buildColumns($table);
        $results = $this->driver->query($request);
        return $this->getAssociativeArrayField($results, $this->builder->getDescribeField(),
              $this->builder->getDescribeValue());
    }

}
