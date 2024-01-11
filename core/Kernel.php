<?php

namespace Core;

use Throwable;
use Dotenv\Dotenv;
use Core\Controller\ErrorController;

class Kernel {

    private Router $router;
    private Security $security;

    public function run() {
        $this->loadEnv();
        try {
            $this->security = new Security();
            $this->router = new Router();
            $this->router->run($_SERVER['REQUEST_URI']);
        } catch (Throwable $th) {
            if ($_ENV['APP_ENV'] === 'dev') {
                error_reporting(E_ALL);
                throw $th;
            } else {
                $error = new ErrorController();
                switch ($th->getCode()) {
                    case '404':
                        $error->notFound();
                        break;
                    case '403':
                        $error->forbidden();
                        break;
                    default:
                        $error->internalServerError();
                        break;
                }
            }
        }
    }

    private function loadEnv(): void {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }
}
