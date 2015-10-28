<?php

namespace Fuz\Component\QuickQuery\Driver;

use Fuz\Component\QuickQuery\Builder\BuilderInterface;

class DriverPDO implements DriverInterface
{
    /**
     * @var \PDO
     */
    protected $dbh;

    /**
     * @var BuilderInterface
     */
    protected $builder;

    public function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * {@inheritdoc}
     */
    public function setBuilder(BuilderInterface $builder)
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function escapeIdentifier($identifier)
    {
        $encloser = $this->builder->getIdentifierEncloser();

        return $encloser.str_replace($encloser, $encloser.$encloser, $identifier).$encloser;
    }

    /**
     * {@inheritdoc}
     */
    public function escapeValue($value)
    {
        return $this->dbh->quote($value);
    }

    /**
     * {@inheritdoc}
     */
    public function query($request, array $params = array())
    {
        $return = null;

        $stmt = $this->dbh->prepare($request);
        if ($stmt === false) {
            return $return;
        }

        if ($stmt->execute($params) === false) {
            return $return;
        }

        if ($this->builder->needToReturnResults($request)) {
            $return = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function insertId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'PDO';
    }
}
