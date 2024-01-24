<?php

namespace Core;

use ReflectionClass;
use Core\Attribute\Entity;
use Core\BlogInterface\UserInterface;

class Security {

    public bool $isLogged = false;
    public ?int $userId = null;
    public ?UserInterface $user = null;

    public function __construct() {
        $this->createSession();
        $this->isLogged = $this->getSession('user') !== null;
        if ($this->isLogged) {
            $this->userId = $this->getSession('user');
            $this->user = $this->getUser();
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

    public function generateToken(string $key): string {
        $token = bin2hex(random_bytes(32));
        $this->addSession($key, $token);
        return $token;
    }

    public function isCsrfTokenValid(string $key, string $token): bool {
        $sessionToken = $this->getSession($key);
        if ($sessionToken === null) {
            return false;
        }
        $this->removeSession($key);
        return hash_equals($sessionToken, htmlspecialchars(strip_tags(trim(addslashes($token)))));
    }

    private function getUser(): ?UserInterface {
        if ($this->userId === null) {
            return null;
        }

        $entities = glob(
            dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Entity' . DIRECTORY_SEPARATOR . '*.php'
        );

        $userRepository = null;
        foreach ($entities as $key => $value) {
            $entity = str_replace('.php', '', $value);
            $entity = substr($entity, strpos($entity, 'Entity'));
            $entity = ltrim($entity, 'Entity\/');
            $reflectionClass = new ReflectionClass('App\\Entity\\' . $entity);
            $interfaces = $reflectionClass->getInterfaceNames();
            if (in_array(UserInterface::class, $interfaces)) {
                $attributes = $reflectionClass->getAttributes(Entity::class);
                $userRepository = $attributes[0]->getArguments()['repository'];
                break;
            }
        }

        if ($userRepository === null) {
            return null;
        } else {
            return (new $userRepository)->find($this->userId);
        }
    }

    public function isGranted(string $levelName): bool {
        if ($this->user === null) {
            return false;
        }

        $levelsWithHeritage = array_merge(
            $this->user->getLevel()->getHeritage() ? explode(',', $this->user->getLevel()->getHeritage()) : [],
            [$this->user->getLevel()->getName()]
        );

        return in_array(strtolower($levelName), array_map('strtolower', $levelsWithHeritage));
    }
}
