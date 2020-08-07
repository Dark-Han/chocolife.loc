<?php

namespace Core;

use Exception;

class Router
{
    private $routes = [];
    private $params = [];

    public function __construct()
    {
        $this->loadRoutes();
    }

    public function loadRoutes(): void
    {
        $routes = require $_SERVER['DOCUMENT_ROOT'] . '/Config/Routes.php';
        foreach ($routes as $route => $params) {
            $this->add($route, $params);
        }
    }

    public function add(string $route, array $params): void
    {
        $route = "#^$route$#";
        $this->routes[$route] = $params;
    }

    public function run()
    {
        if ($this->match()) {
            $controller = 'App\Controllers\\' . $this->params['controller'];
            $action = $this->params['action'];
            $type = $this->params['type'];
            if ($this->checkHandler($controller, $action, $type)) {
                $controller = new $controller;
                $controller->$action($this->getArguments());
            }
        } else {
            throw new Exception('Route is not defined !');
        }
    }

    public function match(): bool
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    private function checkHandler($controller, $action, $type): bool
    {
        if (class_exists($controller)) {
            if (!method_exists($controller, $action)) {
                throw new Exception('Action is not defined !');
            }
        } else {
            throw new Exception('Controller is not defined !');
        }

        if ($_SERVER['REQUEST_METHOD'] !== $type) {
            throw new Exception("This route wait $type method !");
        }

        return true;
    }

    private function getArguments(): ?int
    {
        $url = explode('/', $_SERVER['REQUEST_URI']);
        if (isset($url[3])) {
            return (int)$url[3];
        } else {
            return null;
        }
    }
}
