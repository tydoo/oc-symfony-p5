<?php

namespace Core\Response;

use Core\Router;

class RedirectResponse extends Response {
    public function __construct(string $routeName, array $params = []) {
        $router = new Router();
        header('Location: ' . $router->getUriFromRoute($routeName, $params), true, 302);
    }
}
