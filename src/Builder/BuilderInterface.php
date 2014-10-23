<?php

namespace Fuz\Component\QuickQuery\Builder;

use Fuz\Component\QuickQuery\Driver\DriverInterface;

interface BuilderInterface
{

    public function setDriver(DriverInterface $driver);

    public function buildSelect($table, array $wheres = array ());

    public function buildCount($table, array $wheres = array ());

    public function buildHas($table, array $wheres = array ());

    public function buildInsert($table, array $columnsValues, $ignore = false);

    public function buildInsertUpdate($table, array $columnsValues);

    public function buildUpdate($table, array $columnsValues, array $wheres = array ());

    public function buildDelete($table, array $wheres = array ());

    public function buildTruncate($table);

    public function buildExists($table);

    public function buildDescribe($table);

    public function buildGetConnectionId();

    public function buildKill($id);

    public function buildBegin();

    public function buildRollback();

    public function buildCommit();

    public function getIdentifierEncloser();

    public function getDescribeField();

    public function getDescribeValue();

    public function needToReturnResults($request);

    public function getName();
}
