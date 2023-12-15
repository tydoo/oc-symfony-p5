<?php

namespace Core\Response;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Response {
    public function __construct(string $path, array $params = [], int $code = 200) {
        http_response_code($code);
        $templatesDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates';
        $loader = new FilesystemLoader($templatesDir);
        $twig = new Environment($loader, [
            'cache' => false,
        ]);
        echo $twig->render($path, $params);
    }
}
