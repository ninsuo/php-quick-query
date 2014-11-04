<?php

require(__DIR__.'/../vendor/autoload.php');

use Fuz\Component\QuickQuery\QuickDatabase;
use Fuz\Component\QuickQuery\Driver\DriverPDO;
use Fuz\Component\QuickQuery\Builder\BuilderMysql;

$pdo = new \PDO("mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->query("
    CREATE TABLE IF NOT EXISTS user (
      id INT NOT NULL PRIMARY KEY auto_increment,
      lastname VARCHAR(32) NOT NULL,
      firstname VARCHAR(32) NOT NULL
    )
");

$db = new QuickDatabase(new DriverPDO($pdo), new BuilderMysql());

// todo