<?php

namespace Core\Response;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Response {

    private Environment $twig;

    public function __construct(string $path, array $params = [], int $code = 200) {
        http_response_code($code);
        $templatesDir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'templates';
        $loader = new FilesystemLoader($templatesDir);
        $this->twig = new Environment($loader, [
            'cache' => false,
        ]);
        $this->loadTwigExtensions();
        echo $this->twig->render($path, $params);
    }

    private function loadTwigExtensions() {
        $extentions_core = glob(
            dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Twig' . DIRECTORY_SEPARATOR . '*.php'
        );

        $extentions_src = glob(
            dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Twig' . DIRECTORY_SEPARATOR . '*.php'
        );

        foreach (array_merge($extentions_core, $extentions_src) as $extention) {
            $extention = str_replace('.php', '', $extention);
            $extention = substr($extention, strpos($extention, 'Twig'));
            $extention = ltrim($extention, 'Twig\/');
            $extention = 'App\\Twig\\' . $extention;
            $this->twig->addExtension(new $extention());
        }
    }
}
