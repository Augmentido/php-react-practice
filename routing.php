<?php
namespace Routing;

/**
 * Hadles routing for HTTP requests
 * @param string $method
 * @param string $path
 * @param array $routes
 * 
 * @return bool
 */
function route($method, $path, $routes){
    if(str_ends_with($path, '/')){
        $path = substr($path, 0, -1);
    }
    if(str_starts_with($path, '/')){
        $path = substr($path, 1);
    }

    foreach($routes as $route){
        if($route[0] != $method){
            continue;
        }
        if(route_corresponds($path, $route[1])){
            return route_run($path, $route[1], $route[2], $route[3]);
        }
    }
    return false;
}

/**
 * Compare actual path with route string described in routes list.
 * @param string $path_actual
 * @param string $route_string
 * 
 * @return bool
 */
function route_corresponds($path_actual, $route_string){
    // if there is no parameters in route - we can just compare strings
    if(false === strpos($route_string, ':') ){
        return $path_actual == $route_string;
    }
    $route_string = explode('/', $route_string);
    $path_actual = explode('/', $path_actual);

    // if pieces count does not corresponds - route does not corresponds too
    if(count($route_string) != count($path_actual)){
        return false;
    }

    // next, let`s check every piece
    foreach($route_string as $i => $route_piece){
        if(!str_starts_with($route_piece, ':') AND $route_piece != $path_actual[$i]){
            return false;
        }
    }
    return true;
}

/**
 * Runs a controller described in routes list
 * @param string $path_actual
 * @param string $route_string
 * @param string $route_controller
 * @param string $route_method
 * 
 * @return bool
 */
function route_run($path_actual, $route_string, $route_controller, $route_method){
    // 1. get params (if any)
    $params = [];
    if(false !== strpos($route_string, ':') ){
        $route_string = explode('/', $route_string);
        $path_actual = explode('/', $path_actual);
        foreach($route_string as $i => $route_piece){
            if(str_starts_with($route_piece, ':') AND $route_piece != $path_actual[$i]){
                $params[substr($route_piece, 1)] = $path_actual[$i];
            }
        }
    }

    // 2. initialize controller
    $fn = ROOT.'/Controller/'.$route_controller.'.php';
    if(!file_exists($fn)){
        return false; // add call to error 500 controller here
    }
    require_once $fn;

    // 3. run
    $controller =  '\\Controller\\'.$route_controller.'\\'.$route_controller;
    $controller = new $controller();
    $controller->{$route_method}($params);
    return true;
}