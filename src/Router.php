<?php

namespace App;

use ReflectionClass;
use ReflectionMethod;
use App\Controller\ErrorController;

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
            return $route->getPath() === $uri && in_array($_SERVER['REQUEST_METHOD'], $route->getMethods());
        });

        if (count($route) === 1) {
            $route = array_shift($route);
            $controller = 'App\\Controller\\' . $route->getController();
            $controller = new $controller();
            $controller->{$route->getAction()}();
        } else {
            $this->loadError404();
        }
    }

    public function getUriFromRoute(string $routeName, array $params = []): string {
        $route = array_filter($this->routes, function (Route $route) use ($routeName) {
            return $route->getName() === $routeName;
        });

        if (count($route) === 1) {
            $route = array_shift($route);
            $uri = $route->getPath();
            if (count($params) > 0) {
                $uri .= '?' . http_build_query($params);
            }
            return $uri;
        } else {
            $this->loadError404();
        }
    }

    private function loadError404() {
        $error = new ErrorController();
        $error->notFound();
    }
}
