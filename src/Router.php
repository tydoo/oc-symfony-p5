<?php

namespace App;

use ReflectionClass;
use ReflectionMethod;

class Router {

    private array $routes = [];

    public function __construct() {
        $this->registerRoutes();
    }

    private function registerRoutes(): void {
        $controllers = glob(__DIR__ . '/Controller/*.php');
        foreach ($controllers as $controller) {
            $controller = str_replace('.php', '', $controller);
            $controller = substr($controller, 12 + strpos($controller, '/Controller/'));
            $reflectionClass = new ReflectionClass('App\\Controller\\' . $controller);
            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
            if (count($methods) > 0) {
                foreach ($methods as $method) {
                    $attributes = $method->getAttributes(Route::class);
                    foreach ($attributes as $attribute) {
                        $route = $attribute->newInstance();
                        $route->setController($controller);
                        $route->setAction($method->getName());
                        $this->routes[] = $route;
                    }
                }
            }
        }
    }

    public function run(string $uri) {
        $route = array_filter($this->routes, function (Route $route) use ($uri) {
            return $route->getPath() === $uri;
        });

        if (count($route) === 1) {
            $route = array_shift($route);
            $controller = 'App\\Controller\\' . $route->getController();
            $controller = new $controller();
            $controller->{$route->getAction()}();
        } else {
            $this->redirectToRoute('error.404');
        }
    }

    public function redirectToRoute(string $routeName, array $params = []): void {
        $route = array_filter($this->routes, function (Route $route) use ($routeName) {
            return $route->getName() === $routeName;
        });

        if (count($route) === 1) {
            $route = array_shift($route);
            $uri = $route->getPath();
            if (count($params) > 0) {
                $uri .= '?' . http_build_query($params);
            }
            header('Location: ' . $uri, true, 302);
        } else {
            $this->redirectToRoute('error.404');
        }
    }
}
