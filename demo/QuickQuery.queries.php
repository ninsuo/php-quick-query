<?php

require(__DIR__ . '/../vendor/autoload.php');

use Fuz\Component\QuickQuery\QuickDatabase;
use Fuz\Component\QuickQuery\Driver\DriverPDO;
use Fuz\Component\QuickQuery\Builder\BuilderMysql;

$pdo = new \PDO("mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->query("DROP TABLE user");

$pdo->query("
    CREATE TABLE user (
      id INT NOT NULL PRIMARY KEY auto_increment,
      lastname VARCHAR(32) NOT NULL UNIQUE,
      firstname VARCHAR(32) NOT NULL DEFAULT 'Bob'
    )
");

$db = new QuickDatabase(new DriverPDO($pdo), new BuilderMysql());

/* * ****************************************************** *

  Querying tables

  Querying tables is no more than using the table as a property of your
  database, and calling the method corresponding to the action you're
  doing.

 * * ****************************************************** * */

$db->user->insert(array (
        'firstname' => 'alain',
        'lastname' => 'tiemblo',
));

/*
 * Equivalent to:
 *
 *      INSERT INTO `user` ( `firstname`, `lastname`) VALUES ( 'alain', 'tiemblo' )
 *
 */

echo sprintf("01 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

$db->user->insert(array (
        'firstname' => 'mickael',
        'lastname' => 'steller',
   ), true);

/*
 * Equivalent to:
 *
 *      INSERT IGNORE INTO `user` ( `firstname`, `lastname` ) VALUES ( 'mickael', 'steller' )
 *
 */

echo sprintf("02 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

$db->user->insertUpdate(array (
        'firstname' => 'mike',
        'lastname' => 'steller',
));

/*
 * Equivalent to:
 *
 *      INSERT INTO `user` (`firstname`, `lastname` ) VALUES ( 'mike', 'steller' )
 *      ON DUPLICATE KEY UPDATE `firstname` = 'mike', `lastname` = 'steller'
 *
 */

echo sprintf("03 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

$db->user->update(array (
        'firstname' => 'john',
   ), array (
        'lastname' => 'steller',
));

/*
 * Equivalent to:
 *
 *      UPDATE `user` SET `firstname` = 'john' WHERE `lastname` = 'steller'
 *
 */

echo sprintf("04 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

$user = $db->user->select(array (
        'firstname' => 'alain',
        'lastname' => 'tiemblo',
));

/*
 * Equivalent to:
 *
 *      SELECT * FROM `user` WHERE `firstname` = 'alain' AND `lastname` = 'tiemblo'
 *
 */

echo sprintf("05 - I selected: %s\n", $user[0]['firstname']);

/* * ******************************************************* * */

$has = $db->user->has(array (
        'firstname' => 'alain',
   ));

/*
 * Returns true if the following request returns results:
 *
 *      SELECT * FROM `user` WHERE `firstname` = 'alain' AND `lastname` = 'tiemblo' LIMIT 1
 *
 */

echo sprintf("06 - Does the row exist? %s\n", $has ? 'yes' : 'no');

/* * ******************************************************* * */

$db->user->delete(array (
        'lastname' => 'steller',
));

/*
 * Equivalent to:
 *
 *      DELETE FROM `user` WHERE `lastname` = 'steller'
 *
 */

echo sprintf("07 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

$db->user->truncate();

/*
 * Empties the table:
 *
 *      TRUNCATE TABLE `user`
 *
 */

echo sprintf("08 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

$userExists = $db->user->exists();
$bobExists = $db->bob->exists();

/*
 * Returns true if the following query return results:
 *
 *      SHOW TABLES LIKE 'user'
 *
 */

echo sprintf("09 - Table user exists: %s, and table bob exists: %s\n", $userExists ? 'yes' : 'no',
   $bobExists ? 'yes' : 'no');

/* * ******************************************************* * */

$describe = $db->user->describe();

/*
 * Returns the table's description, the format may vary according to the database you're querying.
 *
 *      DESCRIBE 'user'
 *
 */

echo sprintf("10 - Table user has %d columns:\n", count($describe));
foreach ($describe as $column)
{
    echo sprintf("- Column %s of type %s\n", $column['Field'], $column['Type']);
}

/* * ******************************************************* * */

$columns = $db->user->columns();

/*
 * Returns the table's column list using a describe:
 *
 *      DESCRIBE 'user'
 *
 */

echo sprintf("11 - Table user has %d columns: %s.\n", count($columns), implode(', ', $columns));

/* * ******************************************************* * */

$empty = $db->user->emptyRow();

/*
 * Returns an empty row, as an associative array column => default value.
 *
 * This method also uses a describe:
 *
 *      DESCRIBE 'user'
 *
 */

echo "12 - An empty row of the user table looks like this:\n";

var_dump($empty);

/* * ****************************************************** * *

  All table methods are also available using the $db->method('table', parameters) format.
  For example, instead of:
  $db->user->select(array('user_id' => 42))
  you can use:
  $db->select('user', array('user_id' => 42))

 * * ****************************************************** * */

/* * ****************************************************** *

  Querying database

  Other methods are available on the $db object, to do some general
  actions.

 * * ****************************************************** * */

$conn_id = $db->getConnectionId();

/*
 * Returns the connection id:
 *
 *      SELECT CONNECTION_ID()
 *
 */

echo sprintf("13 - Connection id = %d\n", $conn_id);

/* * ******************************************************* * */

$db->begin();

for ($i = 0; ($i < 10); $i++)
{
    $db->user->insert(array (
            'firstname' => "first{$i}",
            'lastname' => "last{$i}",
    ));
}

$db->rollback();

/*
 * Transaction:
 * - begin() starts a transaction
 * - rollback() ends a transation: all changes are cancelled.
 *
 *      BEGIN
 *      -- lots of queries
 *      ROLLBACK
 *
 */

echo sprintf("14 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

$db->begin();

for ($i = 0; ($i < 10); $i++)
{
    $db->user->insert(array (
            'firstname' => "first{$i}",
            'lastname' => "last{$i}",
    ));
}

$db->commit();

/*
 * Transaction:
 * - begin() starts a transaction
 * - commit() ends a transation: all changes are saved.
 *
 *      BEGIN
 *      -- lots of queries
 *      COMMIT
 */

echo sprintf("15 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

try
{
    $db->kill($conn_id);
}
catch (\PDOException $ex)
{
    echo "16 - Sucide!\n";
}

/*
 * Kills a connection by ID:
 *
 *      KILL 42
 *
 */

