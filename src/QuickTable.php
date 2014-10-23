<?php

namespace Fuz\Component\QuickQuery;

use Fuz\Component\QuickQuery\Base\BaseQuickQuery;
use Fuz\Component\QuickQuery\Builder\BuilderInterface;
use Fuz\Component\QuickQuery\Driver\DriverInterface;

class QuickTable extends BaseQuickQuery
{

    protected $table;

    public function __construct(DriverInterface $driver, BuilderInterface $builder, $table)
    {
        parent::__construct($driver, $builder);
        $this->table = $table;
    }

    public function select(array $wheres = array ())
    {
        return parent::select($this->table, $wheres);
    }

    public function asSingleRow(array $wheres = array ())
    {
        $results = parent::select($this->table, $wheres);
        return parent::getSingleRow($results);
    }

    public function asSingleField($field, array $wheres = array ())
    {
        $results = parent::select($this->table, $wheres);
        return parent::getSingleField($results, $field);
    }

    public function asArray(array $wheres = array ())
    {
        $results = parent::select($this->table, $wheres);
        return $results;
    }

    public function asArrayField($field, array $wheres = array ())
    {
        $results = parent::select($this->table, $wheres);
        return parent::getArrayField($results, $field);
    }

    public function asAssociativeArray($key, array $wheres = array ())
    {
        $results = parent::select($this->table, $wheres);
        return parent::getAssociativeArray($results, $key);
    }

    public function asAssociativeArrayField($key, $field, array $wheres = array ())
    {
        $results = parent::select($this->table, $wheres);
        return parent::getAssociativeArrayField($results, $key, $field);
    }

    public function count(array $wheres = array ())
    {
        return parent::count($this->table, $wheres);
    }

    public function has(array $wheres = array ())
    {
        return parent::has($this->table, $wheres);
    }

    public function insert(array $columnsValues, $ignore = false)
    {
        return parent::insert($this->table, $columnsValues, $ignore);
    }

    public function insertUpdate(array $columnsValues)
    {
        return parent::insertUpdate($this->table, $columnsValues);
    }

    public function update(array $columnsValues, array $wheres = array ())
    {
        return parent::update($this->table, $columnsValues, $wheres);
    }

    public function delete(array $wheres = array ())
    {
        return parent::delete($this->table, $wheres);
    }

    public function truncate()
    {
        return parent::truncate($this->table);
    }

    public function exists()
    {
        return parent::exists($this->table);
    }

    public function describe()
    {
        return parent::describe($this->table);
    }

    public function columns()
    {
        return parent::columns($this->table);
    }

    public function emptyRow()
    {
        return parent::emptyRow($this->table);
    }

}
