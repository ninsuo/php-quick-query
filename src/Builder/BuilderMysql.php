<?php

namespace Fuz\Component\QuickQuery\Builder;

use Fuz\Component\QuickQuery\Driver\DriverInterface;

class BuilderMysql implements BuilderInterface
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * {@inheritdoc}
     */
    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildSelect($table, array $wheres = array())
    {
        $request = '';

        $request .= 'SELECT * FROM ';
        $request .= $this->driver->escapeIdentifier($table);
        $request .= ' WHERE 1 ';

        foreach ($wheres as $field => $value) {
            $request .= ' AND ';
            $request .= $this->driver->escapeIdentifier($field);
            if (is_null($value)) {
                $request .= ' IS NULL ';
            } else {
                $request .= ' = ';
                $request .= $this->driver->escapeValue($value);
            }
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildCount($table, array $wheres = array())
    {
        $request = '';

        $request .= 'SELECT COUNT(*) AS `count` FROM ';
        $request .= $this->driver->escapeIdentifier($table);
        $request .= ' WHERE 1 ';

        foreach ($wheres as $field => $value) {
            $request .= ' AND ';
            $request .= $this->driver->escapeIdentifier($field);
            if (is_null($value)) {
                $request .= ' IS NULL ';
            } else {
                $request .= ' = ';
                $request .= $this->driver->escapeValue($value);
            }
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildHas($table, array $wheres = array())
    {
        $request = '';

        $request .= 'SELECT * FROM ';
        $request .= $this->driver->escapeIdentifier($table);
        $request .= ' WHERE 1 ';

        foreach ($wheres as $field => $value) {
            $request .= ' AND ';
            $request .= $this->driver->escapeIdentifier($field);
            if (is_null($value)) {
                $request .= ' IS NULL ';
            } else {
                $request .= ' = ';
                $request .= $this->driver->escapeValue($value);
            }
        }

        $request .= ' LIMIT 1';

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildInsert($table, array $columnsValues, $ignore = false)
    {
        $request = '';

        $request .= 'INSERT ';
        if ($ignore) {
            $request .= 'IGNORE ';
        }
        $request .= 'INTO ';
        $request .= $this->driver->escapeIdentifier($table);
        $request .= ' ( ';

        foreach (array_keys($columnsValues) as $key => $column) {
            if ($key > 0) {
                $request .= ' , ';
            }
            $request .= $this->driver->escapeIdentifier($column);
        }

        $request .= ' ) VALUES ( ';

        foreach (array_values($columnsValues) as $key => $value) {
            if ($key > 0) {
                $request .= ' , ';
            }
            if (is_null($value)) {
                $request .= 'NULL';
            } else {
                $request .= $this->driver->escapeValue($value);
            }
        }

        $request .= ' ) ';

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildInsertUpdate($table, array $columnsValues)
    {
        $request = '';

        $request .= 'INSERT INTO ';
        $request .= $this->driver->escapeIdentifier($table);
        $request .= ' ( ';

        foreach (array_keys($columnsValues) as $key => $column) {
            if ($key > 0) {
                $request .= ' , ';
            }
            $request .= $this->driver->escapeIdentifier($column);
        }

        $request .= ' ) VALUES ( ';

        foreach (array_values($columnsValues) as $key => $value) {
            if ($key > 0) {
                $request .= ' , ';
            }
            if (is_null($value)) {
                $request .= 'NULL';
            } else {
                $request .= $this->driver->escapeValue($value);
            }
        }

        $request .= ' ) ON DUPLICATE KEY UPDATE ';

        $count = 0;
        foreach ($columnsValues as $column => $value) {
            if ($count > 0) {
                $request .= ', ';
            }
            $request .= $this->driver->escapeIdentifier($column);
            $request .= ' = ';
            if (is_null($value)) {
                $request .= 'NULL';
            } else {
                $request .= $this->driver->escapeValue($value);
            }
            ++$count;
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildUpdate($table, array $columnsValues, array $wheres = array())
    {
        $request = '';

        $request .= 'UPDATE ';
        $request .= $this->driver->escapeIdentifier($table);
        $request .= 'SET ';

        $count = 0;
        foreach ($columnsValues as $column => $value) {
            if ($count > 0) {
                $request .= ', ';
            }
            $request .= $this->driver->escapeIdentifier($column);
            $request .= ' = ';
            if (is_null($value)) {
                $request .= 'NULL';
            } else {
                $request .= $this->driver->escapeValue($value);
            }
            ++$count;
        }

        $request .= ' WHERE 1 ';

        foreach ($wheres as $field => $value) {
            $request .= ' AND ';
            $request .= $this->driver->escapeIdentifier($field);
            if (is_null($value)) {
                $request .= ' IS NULL ';
            } else {
                $request .= ' = ';
                $request .= $this->driver->escapeValue($value);
            }
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDelete($table, array $wheres = array())
    {
        $request = '';

        $request .= 'DELETE FROM ';
        $request .= $this->driver->escapeIdentifier($table);
        $request .= ' WHERE 1 ';

        foreach ($wheres as $field => $value) {
            $request .= ' AND ';
            $request .= $this->driver->escapeIdentifier($field);
            if (is_null($value)) {
                $request .= ' IS NULL ';
            } else {
                $request .= ' = ';
                $request .= $this->driver->escapeValue($value);
            }
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildTruncate($table)
    {
        $request = '';

        $request .= 'TRUNCATE TABLE ';
        $request .= $this->driver->escapeIdentifier($table);

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildExists($table)
    {
        $request = '';

        $request .= 'SHOW TABLES LIKE ';
        $request .= $this->driver->escapeValue($table);

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDescribe($table)
    {
        $request = '';

        $request .= 'DESCRIBE ';
        $request .= $this->driver->escapeIdentifier($table);

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetConnectionId()
    {
        return 'SELECT CONNECTION_ID() AS `conn_id`';
    }

    /**
     * {@inheritdoc}
     */
    public function buildKill($id)
    {
        $request = '';

        $request .= 'KILL ';
        $request .= $this->driver->escapeValue($id);

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildBegin()
    {
        return 'BEGIN';
    }

    /**
     * {@inheritdoc}
     */
    public function buildCommit()
    {
        return 'COMMIT';
    }

    /**
     * {@inheritdoc}
     */
    public function buildRollback()
    {
        return 'ROLLBACK';
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierEncloser()
    {
        return '`';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescribeField()
    {
        return 'Field';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescribeValue()
    {
        return 'Default';
    }

    /**
     * {@inheritdoc}
     */
    public function needToReturnResults($request)
    {
        return (in_array(strtoupper(substr(trim($request), 0, strpos(trim($request), ' '))),
              array('SELECT', 'SHOW', 'DESCRIBE')));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mysql';
    }
}
