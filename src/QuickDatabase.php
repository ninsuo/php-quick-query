<?php

namespace Fuz\Component\QuickQuery;

use Fuz\Component\QuickQuery\Base\BaseQuickQuery;
use Fuz\Component\QuickQuery\Builder\BuilderInterface;
use Fuz\Component\QuickQuery\Driver\DriverInterface;

class QuickDatabase extends BaseQuickQuery
{
    public function __construct(DriverInterface $driver, BuilderInterface $builder)
    {
        parent::__construct($driver, $builder);
        $this->driver->setBuilder($builder);
        $this->builder->setDriver($driver);
    }

    public function getConnectionId()
    {
        return $this->asSingleField(
              $this->builder->buildGetConnectionId(), 'conn_id'
        );
    }

    public function kill($id)
    {
        return $this->driver->query(
              $this->builder->buildKill($id)
        );
    }

    public function begin()
    {
        return $this->driver->query(
              $this->builder->buildBegin()
        );
    }

    public function rollback()
    {
        return $this->driver->query(
              $this->builder->buildRollback()
        );
    }

    public function commit()
    {
        return $this->driver->query(
              $this->builder->buildCommit()
        );
    }

    public function __isset($table)
    {
        return $this->tableExists($table);
    }

    public function __get($table)
    {
        return new QuickTable($this->driver, $this->builder, $table);
    }
}
