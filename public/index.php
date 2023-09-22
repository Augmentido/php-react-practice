<?php

$uri = explode('?',$_SERVER['REQUEST_URI'],2);
if(str_starts_with($uri[0], '/tasks')){
    // for dev environment
    header('Access-Control-Allow-Origin: http://localhost:3000');
    // backend api goes here
    define('PUBLIC_ROOT', __DIR__);
    include '../api/app.php';
    exit;
}

// ReactJS frontend goes here
include 'index.html';

/*
1. You need to run two web-servers in development environment:

cd public
php -S localhost:8000 
# php server for api will run on localhost:8000

cd public
npm start
# react server will run on localhost:3000


2. You can run only one web server in production (apache or nginx)
with public root in /build directory 
cd build
php -S localhost:443 

*/