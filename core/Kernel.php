<?php

namespace Core;

use Throwable;
use Dotenv\Dotenv;
use App\Controller\ErrorController;

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
                ini_set('display_errors', '1');
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
