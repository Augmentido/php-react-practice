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
    $uri[0], // This variable is defined in index.php as $uri = explode('?',$_SERVER['REQUEST_URI'],2);
    [
        // method,  path,       controller, method to call
        ['GET',    'tasks',     'Tasks', 'list'],
        ['POST',   'tasks',     'Tasks', 'add'],
        ['GET',    'tasks/:id', 'Tasks', 'get'],
        ['DELETE', 'tasks/all', 'Tasks', 'truncate'],
        ['DELETE', 'tasks/:id', 'Tasks', 'delete'],
        ['PATCH',  'tasks/:id', 'Tasks', 'update'],
        ['OPTIONS','tasks',     'Tasks', 'httpOptions'],
        ['OPTIONS','tasks/:id', 'Tasks', 'httpOptions'],
    ]
);
