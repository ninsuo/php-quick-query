QuickQuery Component
===================

Disclamer
------------------------------

This library is not optimized at all and should only be used for **quick & dirty** development (quick maintainance, advanced customer support...)

What is QuickQuery ?
------------------------------

Are you looking for a way to query your databases as fast as possible to build quick scripts and small apps? Don't look further, you're at the right place! QuickQuery is a small PHP component that helps doing simple sql queries without the need of typing them, nor generating entities or write a mapping.

This component uses magic methods to build your queries:

```php
    $db->user->asArray(array (
            'username' => 'ninsuo',
    ));
```

Is the equivalent for:

```mysql
    SELECT * FROM `user` WHERE `username` = 'ninsuo'
```

This component was a proof of concept, but I surprised myself taking it from my scratch box each time I needed a to build small scripts requiring database queries. So I decided to clean and share it, enjoy!

----------


Installation
-------------

### Install Composer

If you have curl, you can use:

`curl -sS https://getcomposer.org/installer | php`

Else, you can use the PHP method instead:

`php -r "readfile('https://getcomposer.org/installer');" | php`

### Add the following to your `composer.json`:

```json
{
    "require": {
        "ninsuo/php-quick-query": "dev-master"
    }
}
```

### Update

`php composer.phar update`


Basic Usage
-------------

You can get started with QuickQuery using the following code:

```php
<?php

require(__DIR__ . '/../vendor/autoload.php');

use Fuz\Component\QuickQuery\QuickDatabase;
use Fuz\Component\QuickQuery\Driver\DriverPDO;
use Fuz\Component\QuickQuery\Builder\BuilderMysql;

$pdo = new \PDO("mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db = new QuickDatabase(new DriverPDO($pdo), new BuilderMysql());
```
If you need samples, you can look at the [demo](https://github.com/ninsuo/php-quick-query/demo) directory.

Documentation
-------------



<!-- toc -->

* [What is QuickQuery ?](#what-is-quickquery)
* [Installation](#installation)
  * [Install Composer](#install-composer)
  * [Add the following to your `composer.json`:](#add-the-following-to-your-composerjson)
  * [Update](#update)
* [Basic Usage](#basic-usage)
* [Documentation](#documentation)
  * [Querying tables](#querying-tables)
    * [Insert data:](#insert-data)
    * [Insert row and ignore duplicate keys:](#insert-row-and-ignore-duplicate-keys)
    * [Insert row and update on duplicate keys:](#insert-row-and-update-on-duplicate-keys)
    * [Update row(s):](#update-rows)
    * [Select row(s):](#select-rows)
    * [Delete row(s):](#delete-rows)
    * [Truncate table:](#truncate-table)
    * [Check if a table exists:](#check-if-a-table-exists)
    * [Describe a table:](#describe-a-table)
    * [Get table columns:](#get-table-columns)
    * [Get an empty row:](#get-an-empty-row)
  * [Database queries](#database-queries)
    * [Get connection ID:](#get-connection-id)
    * [Begin a transaction:](#begin-a-transaction)
    * [Rollback a transaction:](#rollback-a-transaction)
    * [Commit a transaction:](#commit-a-transaction)
    * [Kill a connection:](#kill-a-connection)
  * [Doing native requests (JOIN, GROUP BY and so on...)](#doing-native-requests-join-group-by-and-so-on)
* [Extending](#extending)
  * [Driver](#driver)
  * [Builder](#builder)

<!-- toc stop -->


### Querying tables

Querying tables is no more than using the table as a property of your database, and calling the method corresponding to the action you're doing.

#### **Insert data**:

```php
$db->user->insert(array (
        'firstname' => 'alain',
        'lastname' => 'tiemblo',
));
```

Equivalent to the query:

```sql
INSERT INTO `user` ( `firstname`, `lastname`) VALUES ( 'alain', 'tiemblo' )
```


#### **Insert row and ignore duplicate keys**:

```php
$db->user->insert(array (
        'firstname' => 'mickael',
        'lastname' => 'steller',
), true);
```

Equivalent to the query:

```sql
INSERT IGNORE INTO `user` ( `firstname`, `lastname` ) VALUES ( 'mickael', 'steller' )
```


####  **Insert row and update on duplicate keys**:

```php
$db->user->insertUpdate(array (
        'firstname' => 'mike',
        'lastname' => 'steller',
));
```

Equivalent to the query:

```sql
INSERT INTO `user` (`firstname`, `lastname` ) VALUES ( 'mike', 'steller' )
ON DUPLICATE KEY UPDATE `firstname` = 'mike', `lastname` = 'steller'
```


#### **Update row(s)**:

```php
$db->user->update(array (
        'firstname' => 'john',
   ), array (
        'lastname' => 'steller',
));
```

Equivalent to the query:

```sql
UPDATE `user` SET `firstname` = 'john' WHERE `lastname` = 'steller'
```


#### **Select row(s)**:

```php
$user = $db->user->select(array (
        'firstname' => 'alain',
        'lastname' => 'tiemblo',
));
```

Equivalent to the query:

```sql
SELECT * FROM `user` WHERE `firstname` = 'alain' AND `lastname` = 'tiemblo'
```

Notes:

- `->select($wheres = array())` and `->asArray($wheres = array())` return an array containing all rows as associative array

```php
array(
	array('firstname' => 'alain', 'lastname' => 'tiemblo'),
	array('firstname' => 'john', 'lastname' => 'steller'),
)
```

- `->asArrayField('firstname', $wheres = array())` returns an array containing all values for the given column

```php
array(
	'alain',
	'john',
)
```

- `->asAssociativeArray('lastname', $wheres = array())` returns an associative array containing all rows, using the given column as key.

```php
array(
	'tiemblo' => array('firstname' => 'alain', 'lastname' => 'tiemblo'),
	'steller' => array('firstname' => 'john', 'lastname' => 'steller'),
)
```

- `->asAssociativeArrayField('lastname', 'firstname', $wheres = array())` returns an associative array made using 2 columns of each row.

```php
array(
	'tiemblo' => 'alain',
	'steller' => 'john',
)
```

- `->asSingleRow($wheres = array())` returns a single row.

```php
array('firstname' => 'alain', 'lastname' => 'tiemblo')
```

- `->asSingleField('firstname', $wheres = array())` returns a column of the first given row

```php
'alain'
```

**Check if a row exists**:

```php
$has = $db->user->has(array (
        'lastname' => 'tiemblo',
));
```

Equivalent to the query:

```sql
SELECT * FROM `user` WHERE `firstname` = 'alain' AND `lastname` = 'tiemblo' LIMIT 1
```


#### **Delete row(s)**:

```php
$db->user->delete(array (
        'lastname' => 'steller',
));
```

Equivalent to the query:

```sql
DELETE FROM `user` WHERE `lastname` = 'steller'
```


#### **Truncate table**:

```php
$db->user->truncate();
```

Equivalent to the query:

```sql
TRUNCATE TABLE `user`
```


#### **Check if a table exists**:

```php
$exists = isset($db->user);
```

or

```php
$exists = $db->user->exists();
```

Returns true if the following query gives result:

```sql
SHOW TABLES LIKE 'user'
```


#### **Describe a table**:

```php
$describe = $db->user->describe();
```

Equivalent to the query:

```sql
DESCRIBE 'user'
```

#### **Get table columns**:

```php
$columns = $db->user->columns();
```

Uses the Field column (in MySQL, may vary) of the query:

```sql
DESCRIBE 'user'
```

To return an array containing the list of columns in the user table.


#### **Get an empty row**:

```php
$empty = $db->user->emptyRow();
```

Uses the Field and the Default columns (in MySQL, may vary) of the query:

```sql
DESCRIBE 'user'
```

To return an associative array having the column name as key, and the default column value as value.

### Database queries

#### **Get connection ID**:

```php
$conn_id = $db->getConnectionId();
```

Equivalent to the query:

```sql
SELECT CONNECTION_ID()
```


#### **Begin a transaction**:

```php
$db->begin();
```

Equivalent to the query:

```sql
BEGIN
```


#### **Rollback a transaction**:

```php
$db->rollback();
```

Equivalent to the query:

```sql
ROLLBACK
```


#### **Commit a transaction**:

```php
$db->commit();
```

Equivalent to the query:

```sql
COMMIT
```


#### **Kill a connection**:

```php
$db->kill(42);
```

Equivalent to the query:

```sql
KILL 42
```

### Doing native requests (JOIN, GROUP BY and so on...)

You can do native queries by using `$db->query()`:

```php
$db->query("<your native request>", $paramToEscape1, $paramToEscape2, ..);
```

Example:

```php
$db->query("
  UPDATE orders o
  JOIN customers c
  ON o.customer_id = p.id
  SET o.status = 'SHIPPED'
  WHERE o.id = ?
  AND c.email = ?
", $order_id, $customer_email);
```

Note: to select data, you can still use the helpers:


- `$db->select($request, $param1, $_)` returns an array containing all rows as associative array

- `$db->asArray($request, $param1, $_)` returns an array containing all rows as associative array

- `$db->asArrayField('firstname', $request, $param1, $_)` returns an array containing all values for the given column

- `$db->asAssociativeArray('lastname', $request, $param1, $_)` returns an associative array containing all rows, using the given column as key.

- `$db->asAssociativeArrayField('lastname', 'firstname', $request, $param1, $_)` returns an associative array made using 2 columns of each row.

- `$db->asSingleRow($request, $param1, $_)` returns a single row.

- `$db->asSingleField('firstname', $request, $param1, $_)` returns a column of the first given row


Extending
-------------

### Driver

If you prefer using `mysqli`, `doctrine`, `codeigniter` or other drivers to access your databases, you'll need to implement a new Driver that implements the [DriverInterface](https://github.com/ninsuo/php-quick-query/Driver/DriverInterface). It is well documented itself so I'll not get further here.

### Builder

If you are using another database than MySQL, you can implement another query builder by implementing the [BuilderInterface](https://github.com/ninsuo/php-quick-query/Builder/BuilderInterface). It is well documented itself so I'll not get further here.
