<?php
include_once "vendor/autoload.php";
$config = include_once "config.php";

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => "mysql",
    'database'  => $config['database'],
    "host"      => $config['host'],
    'username'  => $config['username'],
    'password'  => $config['password'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

function array_mirror($array)
{
    $mirrored = [];
    foreach ($array as $key => $value) {
        $mirrored[$value] = $key;
    }
    return $mirrored;
}