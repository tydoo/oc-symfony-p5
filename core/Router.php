<?php

namespace Core;

use ReflectionClass;
use ReflectionMethod;
use Core\Attribute\Route;
use Core\Attribute\isGranted;
use Core\Controller\ErrorController;

class Router {

    private array $routes = [];
    private readonly Security $security;

    public function __construct() {
        // Chargement des routes du framework
        $this->registerAppRoutes(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'core', 'Core\\Controller\\');

        // Chargement des routes de l'application
        $this->registerAppRoutes(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src', 'App\\Controller\\');

        $this->security = new Security();
    }

    /**
     * @param string $dirControllers Chemin vers le dossier contenant les controllers sans le / de fin
     */
    private function registerAppRoutes($dirControllers, $namespace): void {
        $controllers = glob(
            $dirControllers . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . '*.php'
        );
        foreach ($controllers as $controller) {
            $controller = str_replace('.php', '', $controller);
            $controller = substr($controller, strpos($controller, 'Controller'));
            $controller = ltrim($controller, 'Controller\/');
            $reflectionClass = new ReflectionClass($namespace . $controller);
            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
            if (count($methods) > 0) {
                foreach ($methods as $method) {
                    $attributes = $method->getAttributes(Route::class);
                    $attributesIsGranted = $method->getAttributes(isGranted::class);
                    $isGrantedNameLevel = null;
                    if (count($attributesIsGranted) > 0) {
                        $isGrantedNameLevel = $attributesIsGranted[0]->newInstance()->getNameLevel();
                    }
                    foreach ($attributes as $attribute) {
                        $route = $attribute->newInstance();
                        $route->setController($namespace . $controller);
                        $route->setAction($method->getName());
                        $this->routes[$route->getName()] = [
                            '_route' => $route,
                            '_levelRequired' => $isGrantedNameLevel,
                        ];
                    }
                }
            }
        }
    }

    public function run(string $uri) {
        $route = array_filter($this->routes, function (array $route) use ($uri) {
            $path = $route['_route']->getPath();
            if (strpos($path, '{id}') !== false) {
                $path = str_replace('{id}', '(\d+)', $path);
                if (preg_match("#^{$path}$#", $uri) && in_array($_SERVER['REQUEST_METHOD'], $route['_route']->getMethods())) {
                    return true;
                }
            } else if ($path === $uri && in_array($_SERVER['REQUEST_METHOD'], $route['_route']->getMethods())) {
                return true;
            }
            return false;
        });


        if (count($route) === 1) {
            $route = array_shift($route);
            if ($route['_levelRequired'] !== null) {
                if (!$this->security->isGranted($route['_levelRequired'])) {
                    $this->loadError403();
                }
            }
            $route = $route['_route'];
            $controller = $route->getController();
            $controller = new $controller();
            $id = null;
            if (strpos($route->getPath(), '{id}') !== false) {
                $path = str_replace('{id}', '(\d+)', $route->getPath());
                preg_match("#^{$path}$#", $uri, $matches);
                $id = $matches[1];
            }
            $controller->{$route->getAction()}($id);
        } else {
            $this->loadError404();
        }
    }

    public function getUriFromRoute(string $routeName, array $params = []): string {
        $route = array_filter($this->routes, function (array $route) use ($routeName) {
            return $route['_route']->getName() === $routeName;
        });

        if (count($route) === 1) {
            $route = array_shift($route)['_route'];
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

    private function loadError403() {
        $error = new ErrorController();
        $error->forbidden();
    }
}
