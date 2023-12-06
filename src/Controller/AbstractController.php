<?php

namespace App\Controller;

use App\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AbstractController {
    private function render(string $path, array $params = []) {
        $templatesDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates';
        $loader = new FilesystemLoader($templatesDir);
        $twig = new Environment($loader, [
            'cache' => false,
        ]);
        echo $twig->render($path, $params);
    }

    public function response(string $path, array $params = [], int $code = 200) {
        http_response_code($code);
        return $this->render($path, $params);
    }

    public function redirectToRoute(string $routeName, array $params = []) {
        $router = new Router();
        return $router->redirectToRoute($routeName, $params);
    }
}
