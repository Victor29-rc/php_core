<?php

namespace libraries;

class Route {

    private static $routes = [];

    public static function get($route, $action) {
        self::$routes['GET'][$route] =  $action;
    }

    public static function post($route, $action) {
        self::$routes['POST'][$route] =  $action;
    }

    public static function dispatch() {

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $uri           = trim($_SERVER['REQUEST_URI'], '/');

        foreach (self::$routes[$requestMethod] as $route => $action) {
            $route = trim($route, '/');

            if(strpos($route,':') !== false) { // if the route matches #:[a-zA-Z]+# regular expression then replace what is after the ':' with the ([a-zA-Z]+) regular expression
                $route = preg_replace('#:[a-zA-Z0-9]+#','([a-zA-Z0-9]+)', $route);
            }

            if(preg_match("#^$route$#", $uri, $matches)) {
                $params = array_slice($matches, 1); //returning the part tha match with the regular expresion

                if(is_callable($action) && !is_array($action)) {
                    $response   = $action(...$params);
                }
                else if(is_array($action)) {
                    $controller = new $action[0];
                    $response   = $controller->{$action[1]}($params);
                }

                if(is_array($response) || is_object($response)) {
                    header('Content-Type: application/json');
                    echo json_encode($response);
                } else {
                    echo $response;
                }
            }

        }

    }
}