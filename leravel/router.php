<?php

class Router
{
    private $getRoutes = [];
    private $postRoutes = [];
    private $putRoutes = [];
    private $deleteRoutes = [];
    private $patchRoutes = [];
    private $optionsRoutes = [];
    private $notFoundCallback = null;

    public function get($route, $callback)
    {
        $this->getRoutes[$route] = $callback;
    }

    public function post($route, $callback)
    {
        $this->postRoutes[$route] = $callback;
    }

    public function put($route, $callback)
    {
        $this->putRoutes[$route] = $callback;
    }

    public function delete($route, $callback)
    {
        $this->deleteRoutes[$route] = $callback;
    }

    public function patch($route, $callback)
    {
        $this->patchRoutes[$route] = $callback;
    }

    public function options($route, $callback)
    {
        $this->optionsRoutes[$route] = $callback;
    }

    public function notFoundCallback($callback)
    {
        $this->notFoundCallback = $callback;
    }

    public function run()
    {
        global $Leravel;
        $method = $_SERVER['REQUEST_METHOD'];
        $method = strtolower($method) . "Routes";
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, "?admin") !== false && $Leravel["settings"]["admin"]["enabled"] == true) {
            require "admin/admin.php";
            return;
        }
        $uri = explode("?", $uri)[0];
        $routes = $this->$method;
        $args = [];
        foreach ($routes as $route => $callback) {
            if ($route == $uri) {
                $callback();
                return;
            }
            if (strpos($route, "{") !== false) {
                $route = str_replace("/", "\/", $route);
                $route = preg_replace("/{(.*?)}/", "(?<$1>.*?)", $route);
                if (preg_match("/^$route$/", $uri, $matches)) {
                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $args[$key] = $value;
                        }
                    }
                    $callback($args);
                    return;
                }
            }
            
        }
        if (array_key_exists($uri, $routes)) {
            $callback = $routes[$uri];
            $callback($args);
        } else {
            if ($this->notFoundCallback) {
                $callback = $this->notFoundCallback;
                $callback();
            } else {
                global $Leravel;
                require $Leravel["settings"]["router"]["404"];
            }
        }
    }
}

function view($view, $data = [])
{
    global $Leravel;
    extract($data);
    if (is_array($view)) {
        foreach ($view as $v) {
            require $Leravel["settings"]["router"]["views"] . "/$v.php";
        }
    } else {
        require $Leravel["settings"]["router"]["views"] . "/$view.php";
    }
}
