<?php

namespace Core\Twig;

use stdClass;
use App\Repository\UserRepository;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension implements GlobalsInterface {

    public function getGlobals(): array {
        return [
            'app' => $this->getApp(),
        ];
    }

    private function getUser(): ?object {
        if (isset($_SESSION['user'])) {
            $userReposiotry = new UserRepository();
            $user = $userReposiotry->findOneBy(['id' => $_SESSION['user']]);
            if ($user) {
                return $user;
            }
        }

        return null;
    }

    private function getApp(): object {
        $app = new stdClass();
        $app->session = $_SESSION;
        $app->cookie = $_COOKIE;
        $app->user = $this->getUser();

        return $app;
    }
}
