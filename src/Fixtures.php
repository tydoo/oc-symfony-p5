<?php

namespace App;

use Dotenv\Dotenv;
use App\Entity\User;
use App\Entity\Level;
use App\Repository\UserRepository;
use App\Repository\LevelRepository;

/**
 * Permet de créer des données de test
 * A lancer une seule fois pour initialiser la base de données
 * A lancer uniquement si la base de données est vide et que les tables viennent d'être créées
 * @run php src/Fixtures.php
 */
class Fixtures {
    public function __construct() {
        $UserRepository = new UserRepository();
        $LevelRepository = new LevelRepository();

        $LevelRepository->save((new Level())->setName('Administrateur'));
        $LevelRepository->save((new Level())->setName('Utilisateur'));

        $UserRepository->save(
            (new User())
                ->setEmail('thomas@tydoo.fr')
                ->setPassword('password')
                ->setFirstname('Thomas')
                ->setLastname('B')
                ->setLevel($LevelRepository->find(1))
        );

        $UserRepository->save(
            (new User())
                ->setEmail('johndoe@gmail.com')
                ->setPassword('password')
                ->setFirstname('John')
                ->setLastname('DOE')
                ->setLevel($LevelRepository->find(2))
        );
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

new Fixtures();
