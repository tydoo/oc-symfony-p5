<?php

namespace Core;

use Exception;
use Leeqvip\Database\Connection;
use Leeqvip\Database\Manager;

class Database {

    public Connection $connection;

    public function __construct() {
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
}
