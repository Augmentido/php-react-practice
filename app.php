<?php

define('ROOT', __DIR__);

// There is no need to use some complex 
// routing system for so small application.
// Let`s just route right here using very simple 
// routing system I have made

require_once ROOT.'/routing.php';

if(!Routing\route( $_SERVER['REQUEST_METHOD'], $_SERVER['PATH_INFO'], [
        // method, path, controller, method to call
        ['GET', 'tasks', 'Tasks', 'list'],
        ['POST', 'tasks', 'Tasks', 'add'],
        ['GET', 'tasks/:id', 'Tasks', 'get'],
        ['DELETE', 'tasks/:id', 'Tasks', 'delete'],
        ['PATCH', 'tasks/:id', 'Tasks', 'update'],
    ]))
{
    header('404 Page not found');
    echo '404 Page not found';
}