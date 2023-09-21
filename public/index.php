<?php

define('PUBLIC_ROOT', __DIR__);

if(str_starts_with($_SERVER['PATH_INFO'], '/tasks')){
    // backend api goes here
    include '../api/app.php';
}else{
    // TODO: frontend will be here
    echo 'Frontend';
}