<?php
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
    return Laracasts\TestDummy\Factory::times($times)->create($class, $attributes);
}
