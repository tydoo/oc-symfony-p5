<?php

namespace Core;

use Throwable;
use Dotenv\Dotenv;
use Core\Controller\ErrorController;

class Kernel {

    private Router $router;

    public function run() {
        $this->loadEnv();
        try {
            $this->createSession();
            $this->router = new Router();
            $this->router->run($_SERVER['REQUEST_URI']);
        } catch (Throwable $th) {
            if ($_ENV['APP_ENV'] === 'dev') {
                error_reporting(E_ALL);
                throw $th;
            } else {
                $error = new ErrorController();
                $error->internalServerError();
            }
        }
    }

    private function loadEnv(): void {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    private function createSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
