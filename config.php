<?php

if(APPLICATION_ENV == 'testing'){
    return [
        'driver' => 'sqlite',
        'database' => 'test.sqlite',
        'host' => 'localhost',
        'username'  => "",
        'password'  => "",
    ];
}
return [
    'driver' => 'sqlite',
    'database' => 'database.sqlite',
    'host' => 'localhost',
    'username'  => "",
    'password'  => "",
];