<?php

namespace Core\Twig;

use stdClass;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension implements GlobalsInterface {

    public function getGlobals(): array {
        return [
            'app' => $this->getApp(),
        ];
    }

    private function getApp(): object {
        $app = new stdClass();
        $app->session = $_SESSION;

        return $app;
    }
}
