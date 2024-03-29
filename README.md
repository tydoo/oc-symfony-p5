[![SymfonyInsight](https://insight.symfony.com/projects/fc70a844-c5b7-4986-a4a6-61b60e52f468/big.svg)](https://insight.symfony.com/projects/fc70a844-c5b7-4986-a4a6-61b60e52f468)

# PROJET 5
## Formation Développeur d'application - PHP/Symfony - OpenClassrooms

### Installation d'un environnement de développement

#### Prérequis
 1. [PHP 8.1](https://www.php.net/downloads) ou supérieur et ses extensions :
	 1. [Mbstring](https://www.php.net/book.mbstring)
	 2. [PDO + PDO Mysql](https://www.php.net/book.pdo)
	 3. [cURL](https://www.php.net/book.curl)
 2. [Composer](https://getcomposer.org/doc/00-intro.md)
 3. Git
 4. [Node JS - Version >=20](https://nodejs.org)
 5. [Docker + Docker Compose](https://www.docker.com/)

### Démarrage de l'environnement de développement
```bash
composer install
docker-compose up -d
npm install --force
npm run build
php -S localhost:8000 -t ./public
```

### Base de donnée
Une fois le serveur de base de données lancé (docker ou autres), utilisez le fichier SQL se trouvant à la racine.

### Fixtures
Une fois la base de données prête, utilisez les fixtures.
```bash
php ./src/Fixtures.php
```

### Utilisateurs
|Utilisateur|Mot de passe|Level|
|:---------------:|:---------------:|:---------------:|
|thomas@tydoo.fr|123456|Administrateur|
|johndoe@gmail.com|123456|Utilisateur|

### Email
Les emails sont capturés ici
http://127.0.0.1:1080/
