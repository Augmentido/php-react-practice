<?php

define('ROOT', __DIR__);

// There is no need to use some complex 
// routing system for so small application.
// Let`s just route right here using very simple 
// routing system I have made

require_once ROOT.'/responder.php';
require_once ROOT.'/routing.php';

if( strpos($_SERVER['HTTP_ACCEPT'], 'application/json') === false 
    AND strpos($_SERVER['HTTP_ACCEPT'], '*/*') === false){
    Responder::e400('This application can only serve in application/json format.');
}

Routing\route( 
    $_SERVER['REQUEST_METHOD'], 
    $_SERVER['PATH_INFO'], [
        // method,  path,       controller, method to call
        ['GET',    'tasks',     'Tasks', 'list'],
        ['POST',   'tasks',     'Tasks', 'add'],
        ['GET',    'tasks/:id', 'Tasks', 'get'],
        ['DELETE', 'tasks/all', 'Tasks', 'truncate'],
        ['DELETE', 'tasks/:id', 'Tasks', 'delete'],
        ['PATCH',  'tasks/:id', 'Tasks', 'update'],
    ]
);
