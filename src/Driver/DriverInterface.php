<?php

namespace Fuz\Component\QuickQuery\Driver;

use Fuz\Component\QuickQuery\Builder\BuilderInterface;

interface DriverInterface
{

    public function setBuilder(BuilderInterface $builder);

    public function escapeIdentifier($identifier);

    public function escapeValue($value);

    public function query($request, array $params = array());

    public function insertId();

    public function getName();

}