<?php

namespace Core;

use App\Repository\UserRepository;

class Security {

    public bool $isLogged = false;
    public ?int $userId = null;

    public function __construct() {
        $this->createSession();
        $this->isLogged = $this->getSession('user') !== null;
        if ($this->isLogged) {
            $this->userId = $this->getSession('user');
        }
    }

    public function addSession(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    public function getSession(string $key) {
        return $_SESSION[$key] ?? null;
    }

    public function removeSession(string $key): void {
        unset($_SESSION[$key]);
    }

    private function createSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
