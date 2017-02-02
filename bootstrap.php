<?php
include_once "vendor/autoload.php";
define('APPLICATION_ENV', preg_match('/phpunit/', $_SERVER['SCRIPT_NAME']) ? 'testing' : 'production');
define('APPLICATION_PATH', dirname(__FILE__));
include_once "helpers.php";
$config = include_once "config.php";

use Illuminate\Database\Capsule\Manager as Capsule;

if ($config['driver'] == 'sqlite') {
    touch($config['database']);
    exec("sqlite3 ".$config['database']." < data.sql");
}

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => $config['driver'],
    'database'  => $config['database'],
    "host"      => $config['host'],
    'username'  => $config['username'],
    'password'  => $config['password'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->bootEloquent();
$capsule->setAsGlobal();