<?php

namespace Fuz\Component\QuickQuery\Builder;

use Fuz\Component\QuickQuery\Driver\DriverInterface;

/**
 * A Builder creates queries in the proper query language.
 * For example, the BuilderMysql creates queries in the Mysql
 * language.
 *
 * All examples below are MySQL representation of what is
 * expected by a method.
 */
interface BuilderInterface
{

    /**
     * Driver is mainly used to escape column names
     * and field values properly when building requests.
     *
     * @param DriverInterface $driver
     * @return BuilderInterface
     */
    public function setDriver(DriverInterface $driver);

    /**
     * Builds a SELECT query:
     *
     * Context:
     *
     * - $table = "user";
     *
     * - $wheres = array(
     *          'firstname' => 'alain',
     *          'lastname' => 'tiemblo',
     *    );
     *
     * Result:
     *
     *      SELECT *
     *      FROM `user`
     *      WHERE 1
     *      AND `firstname` = 'alain'
     *      AND `lastname` = 'tiemblo'
     *
     * @param string $table
     * @param array $wheres
     * @return string
     */
    public function buildSelect($table, array $wheres = array ());

    /**
     * Builds a COUNT query (returns number of rows that matched):
     *
     * Context:
     *
     * - $table = "user";
     *
     * - $wheres = array(
     *          'firstname' => 'alain',
     *    );
     *
     * Result:
     *
     *      SELECT COUNT(*) AS count
     *      FROM `user`
     *      WHERE 1
     *      AND `firstname` = 'alain'
     *
     * Note:
     *
     * => The resulting column name must be "count".
     *
     * @param type $table
     * @param array $wheres
     * @return string
     */
    public function buildCount($table, array $wheres = array ());

    /**
     * Builds a HAS query (should return true if the row exist):
     *
     * Context:
     *
     * - $table = "user";
     *
     * - $wheres = array(
     *          'firstname' => 'alain',
     *          'lastname' => 'tiemblo',
      *    );
     *
     * Result:
     *
     *      SELECT *
     *      FROM `user`
     *      WHERE 1
     *      AND `firstname` = 'alain'
     *      AND `lastname` = 'tiemblo'
     *      LIMIT 1
     *
     * @param type $table
     * @param array $wheres
     * @return string
     */
    public function buildHas($table, array $wheres = array ());

    /**
     * Builds an INSERT query:
     *
     * Context:
     *
     * - $table = "user";
     *
     * - $columnsValue = array(
     *          'firstname' => 'alain',
     *          'lastname' => 'tiemblo',
     *    );
     *
     * - $ignore = false;
     *
     * Result:
     *
     *      INSERT INTO `user` (
     *          `firstname`, `lastname`
     *      ) VALUES (
     *          'alain', 'tiemblo'
     *      )
     *
     * Note:
     *
     * => If $ignore is set to TRUE, an INSERT IGNORE is generated:
     *       duplicate keys are ignored.
     *
     * @param string $table
     * @param array $columnsValues
     * @param bool $ignore
     * @return string
     */
    public function buildInsert($table, array $columnsValues, $ignore = false);

    /**
     * Builds an INSERT ... ON DUPLICATE KEY UPDATE query:
     *
     * Context:
     *
     * - $table = "user";
     *
     * - $columnsValue = array(
     *          'firstname' => 'alain',
     *          'lastname' => 'tiemblo',
     *    );
     *
     * Result:
     *
     *      INSERT INTO `user` (
     *          `firstname`, `lastname`
     *      ) VALUES (
     *          'alain', 'tiemblo'
     *      ) ON DUPLICATE KEY UPDATE
     *          `firstname` = 'alain',
     *          `lastname` = 'tiemblo'
     *
     * @param string $table
     * @param array $columnsValues
     * @return string
     */
    public function buildInsertUpdate($table, array $columnsValues);

    /**
     * Builds an UPDATE query:
     *
     * Context:
     *
     * - $table = "user";
     *
     * - $columnsValue = array(
     *          'firstname' => 'alain',
     *          'lastname' => 'tiemblo',
     *    );
     *
     * - $wheres = array(
     *          'user_id' => 42,
     *    );
     *
     * Result:
     *
     *      UPDATE `user`
     *      SET
     *          `firstname` = 'alain',
     *          `lastname` = 'TIEMBLO'
     *      WHERE 1
     *      AND `user_id` = '42'
     *
     * @param string $table
     * @param array $columnsValues
     * @param array $wheres
     * @return string
     */
    public function buildUpdate($table, array $columnsValues, array $wheres = array ());

    /**
     * Builds a DELETE query:
     *
     * Context:
     *
     * - $table = "user";
     *
     * - $wheres = array(
     *          'firstname' => 'alain',
     *          'lastname' => 'tiemblo',
     *    );
     *
     * Result:
     *
     *      DELETE FROM `user`
     *      WHERE 1
     *      AND `firstname` = 'alain'
     *      AND `lastname` = 'tiemblo'
     *
     * @param string $table
     * @param array $wheres
     * @return string
     */
    public function buildDelete($table, array $wheres = array ());

    /**
     * Builds a TRUNCATE query:
     *
     * Context:
     *
     * - $table = "user";
     *
     * Result:
     *
     *      TRUNCATE TABLE `user`
     *
     * @param string $table
     * @return string
     */
    public function buildTruncate($table);

    /**
     * Builds a query that will return a result if the table exists.
     *
     * Context:
     *
     * - $table = "user";
     *
     * Result:
     *
     *      SHOW TABLES LIKE 'user'
     *
     * @param string $table
     * @return string
     */
    public function buildExists($table);

    /**
     * Builds a query that will return table's column names
     *
     * Context:
     *
     * - $table = "user";
     *
     * Result:
     *
     *      DESCRIBE `user`
     *
     * @param string $table
     * @return string
     */
    public function buildDescribe($table);

    /**
     * Builds a query that should return database's connection id
     *
     * Result:
     *
     *      TRUNCATE TABLE `user`
     *
     * @return string
     */
    public function buildGetConnectionId();

    /**
     * Builds a query that should return database's connection id
     *
     * Context:
     *
     * - $id = 42;
     *
     * Result:
     *
     *      KILL '42'
     *
     * @param int $id
     * @return string
     */
    public function buildKill($id);

    /**
     * Builds a query that begins a transaction
     *
     * Result:
     *
     *      BEGIN
     *
     * @return string
     */
    public function buildBegin();

    /**
     * Builds a query that rollbacks a transaction
     *
     * Result:
     *
     *      ROLLBACK
     *
     * @return string
     */
    public function buildRollback();

    /**
     * Builds a query that commits a transaction
     *
     * Result:
     *
     *      COMMIT
     *
     * @return string
     */
    public function buildCommit();

    /**
     * Returns the right identifier enclosure, used to
     * escape the identifier.
     *
     * Result:
     *
     *      `
     *
     * @return string
     */
    public function getIdentifierEncloser();

    /**
     * The DESCRIBE query built above returns the whole
     * table's description. This method returns the column
     * name that represents the field name.
     *
     * Result:
     *
     *      Field
     *
     * @return string
     */
    public function getDescribeField();

    /**
     * The DESCRIBE query built above returns the whole
     * table's description. This method returns the column
     * name that represents the default value.
     *
     * Result:
     *
     *      Default
     *
     * @return string
     */
    public function getDescribeValue();

    /**
     * This method should return TRUE if the request will return
     * results or not. For example, the request returns results if
     * it begins by SELECT, DESCRIBE or SHOW.
     *
     * @param string $request
     * @return bool
     */
    public function needToReturnResults($request);

    /**
     * Returns the builder's name, this might be useful if a
     * provider is implemented within the final application.
     *
     * @return string
     */
    public function getName();
}
