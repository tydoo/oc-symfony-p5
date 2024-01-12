<?php

namespace Core\Twig;

use stdClass;
use Core\Security;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension implements GlobalsInterface {

    public function getGlobals(): array {
        return [
            'app' => $this->getApp(),
        ];
    }

    private function getApp(): object {
        $security = new Security();

        $app = new stdClass();
        $app->session = $_SESSION;
        $app->cookie = $_COOKIE;
        $app->user = $security->user;

        return $app;
    }
}
