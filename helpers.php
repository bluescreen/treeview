<?php

function dd(){
    die("<pre>".print_r(func_get_args(), 1)."</pre>");
}

function array_mirror($array)
{
    $mirrored = [];
    foreach ($array as $key => $value) {
        $mirrored[$value] = $key;
    }
    return $mirrored;
}


function __($text){
    $args = func_get_args();
    array_shift($args);
    return sprintf($text, $args);
}

function pr_matrix($m)
{
    $out = "";
    foreach ($m as $row) {
        $out .= implode(",", $row) . "\n";
    }
    return $out;
}

function matrix($w, $h, $val = 0)
{
    $m = [];
    for ($r = 0; $r < $w; $r++) for ($c = 0; $c < $h; $c++) $m[$c][$r] = $val;
    return $m;
}

function factory($class, array $attributes = [], $times = 1)
{   
    for($i=0;$i< $times;$i++){
        $faker = Faker\Factory::create("de");
        $factories = include "tests/factories/factories.php";
        if(!isset($factories[$class])){
            throw new Exception("Factory for $class not found");
        }
        $data = call_user_func($factories[$class]);

        $model = new $class();
        $model->fill($data + $attributes);
        $model->save();
    }
    return $model;
}


