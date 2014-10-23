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
      firstname VARCHAR(32) NOT NULL
    )
");

$db = new QuickDatabase(new DriverPDO($pdo), new BuilderMysql());

/* * ****************************************************** * */

$db->user->insert(array (
        'firstname' => 'alain',
        'lastname' => 'tiemblo',
));

/*
 * Equivalent to:
 *
 *      INSERT INTO `user` (
 *          `firstname`, `lastname`
 *      ) VALUES (
 *           'alain', 'tiemblo'
 *      )
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
 *      INSERT IGNORE INTO `user` (
 *          `firstname`, `lastname`
 *      ) VALUES (
 *           'mickael', 'steller'
 *      )
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
 *      INSERT IGNORE INTO `user` (
 *          `firstname`, `lastname`
 *      ) VALUES (
 *           'mickael', 'steller'
 *      )
 *
 */

echo sprintf("03 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

$db->user->insertUpdate(array (
        'firstname' => 'mike',
        'lastname' => 'steller',
));

/*
 * Equivalent to:
 *
 *      INSERT INTO `user` (
 *          `firstname`, `lastname`
 *      ) VALUES (
 *          'alain', 'tiemblo'
 *      ) ON DUPLICATE KEY UPDATE
 *          `firstname` = 'alain',
 *          `lastname` = 'tiemblo'
 *
 */

echo sprintf("04 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

$db->user->update(array (
        'firstname' => 'john',
   ), array (
        'lastname' => 'steller',
));

/*
 * Equivalent to:
 *
 *      UPDATE `user`
 *      SET
 *          `firstname` = 'john',
 *      WHERE 1
 *      AND `lastname` = 'steller'
 *
 */

echo sprintf("05 - There are %d users: %s\n", $db->user->count(), implode(", ", $db->user->asArrayField('firstname')));

/* * ******************************************************* * */

$user = $db->user->select(array (
        'firstname' => 'alain',
        'lastname' => 'tiemblo',
));

// to be continued