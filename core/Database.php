<?php

namespace Core;

use Exception;
use ReflectionClass;
use Core\Attribute\Entity;
use Leeqvip\Database\Manager;
use Leeqvip\Database\Connection;

class Database {

    public Connection $connection;
    public array $entities = [];

    public function __construct() {
        $this->registerEntities();
        $DATABASE_URL = $this->parseDatabaseConnection($_ENV['DATABASE_URL']);

        $this->connection = (new Manager([
            'type'  =>    $DATABASE_URL['scheme'],
            'hostname' => $DATABASE_URL['host'],
            'database' => $DATABASE_URL['database'],
            'username' => $DATABASE_URL['user'],
            'password' => $DATABASE_URL['pass'],
            'hostport' => $DATABASE_URL['port'],
        ]))->getConnection();
    }

    private function parseDatabaseConnection($connectionString) {
        $parsed = parse_url($connectionString);

        if ($parsed === false) {
            throw new Exception("Impossible d'analyser la chaîne de connexion.");
        }

        $path = ltrim($parsed['path'], '/');

        return [
            'scheme' => $parsed['scheme'],
            'host' => $parsed['host'],
            'port' => $parsed['port'],
            'user' => $parsed['user'],
            'pass' => $parsed['pass'],
            'database' => $path,
        ];
    }

    private function registerEntities() {
        $entities = glob(
            dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Entity' . DIRECTORY_SEPARATOR . '*.php'
        );

        foreach ($entities as $key => $value) {
            $entity = str_replace('.php', '', $value);
            $entity = substr($entity, strpos($entity, 'Entity'));
            $entity = ltrim($entity, 'Entity\/');
            $reflectionClass = new ReflectionClass('App\\Entity\\' . $entity);
            $attributes = $reflectionClass->getAttributes(Entity::class);
            foreach ($attributes as $attribute) {
                $newEntity = $attribute->newInstance();
                $newEntity->setClass('App\\Entity\\' . $entity);
                $this->entities[] = $newEntity;
            }
        }
    }
}
