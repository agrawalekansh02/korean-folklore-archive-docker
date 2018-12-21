<?php

// if local
if ($_SERVER['SERVER_NAME']=='localhost'){
    define('SECRET','');
    define('HOST','http://localhost:8888/kfl/');
    define('DB_HOST', '');
    define('DB_USERNAME', '');
    define('DB_PASSWORD', '');
    define('DB_NAME', '');
}
else if ($_SERVER['SERVER_NAME']=='dev.kfolk.cdh.ucla.edu'){
// if test
    define('SECRET','');
    define('HOST','http://dev.kfolk.cdh.ucla.edu/');
    define('DB_HOST', '');
    define('DB_USERNAME', '');
    define('DB_PASSWORD', '');
    define('DB_NAME', '');
}
else if ($_SERVER['SERVER_NAME']=='dev2.kfolk.cdh.ucla.edu'){
// if test
    define('SECRET','');
    define('HOST','https://dev2.kfolk.cdh.ucla.edu/');
    define('DB_HOST', '');
    define('DB_USERNAME', '');
    define('DB_PASSWORD', '');
    define('DB_NAME', '');
}
else if ($_SERVER['SERVER_NAME']=='kfolk.cdh.ucla.edu'){
// if production
    define('SECRET','');
    define('HOST','http://kfolk.cdh.ucla.edu/');
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', '');
    define('DB_PASSWORD', '');
    define('DB_NAME', '');
}