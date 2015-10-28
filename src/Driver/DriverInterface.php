<?php

namespace Fuz\Component\QuickQuery\Driver;

use Fuz\Component\QuickQuery\Builder\BuilderInterface;

/**
 * A Driver manages an SQL Client, and provides all client-specific
 * methods.
 */
interface DriverInterface
{
    /**
     * Builder is sometimes required by the driver, for example
     * when escaping an identifier. All databases will use double
     * quote ( " ) to escape an indentifier, where MySQL does use
     * backtick ( ` ).
     *
     * @param BuilderInterface $builder
     *
     * @return DriverInterface
     */
    public function setBuilder(BuilderInterface $builder);

    /**
     * Escapes an identifier, such as database, table and column
     * names.
     *
     * @param string $identifier
     *
     * @return string
     */
    public function escapeIdentifier($identifier);

    /**
     * Escapes a value to make it safe against injections.
     *
     * @param string $value
     *
     * @return string
     */
    public function escapeValue($value);

    /**
     * Does the given request as a prepared statement, using
     * the given parameters. Should return, if needed (see
     * BuilderInterface::needToReturnResults), all results
     * as an array of rows. All rows should be associative
     * arrays.
     *
     * @param string $request
     * @param array  $params
     *
     * @return null|array
     */
    public function query($request, array $params = array());

    /**
     * Called after a SQL insert, this method should return
     * the last inserted id (in case of auto_increment for
     * example).
     *
     * @return int
     */
    public function insertId();

    /**
     * Returns the driver's name, this might be useful if a
     * provider is implemented within the final application.
     *
     * @return string
     */
    public function getName();
}
