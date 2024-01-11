<?php

namespace App;

use Dotenv\Dotenv;
use App\Entity\User;
use App\Entity\Level;
use App\Entity\Comment;
use App\Entity\BlogPost;
use App\Entity\Category;
use App\Repository\UserRepository;
use App\Repository\LevelRepository;
use App\Repository\CommentRepository;
use App\Repository\BlogPostRepository;
use App\Repository\CategoryRepository;

/**
 * Permet de créer des données de test
 * A lancer une seule fois pour initialiser la base de données
 * A lancer uniquement si la base de données est vide et que les tables viennent d'être créées
 * @run php src/Fixtures.php
 */
class Fixtures {
    public function __construct() {
        echo "Création des données de test ...\n";

        $UserRepository = new UserRepository();
        $LevelRepository = new LevelRepository();
        $CategoryRepository = new CategoryRepository();
        $BlogPostRepository = new BlogPostRepository();
        $CommentRepository = new CommentRepository();

        $LevelRepository->save((new Level())->setName('Administrateur'));
        $LevelRepository->save((new Level())->setName('Utilisateur'));

        /**
         * User
         * Password : 123456
         */
        $UserRepository->save(
            (new User())
                ->setEmail('thomas@tydoo.fr')
                ->setPassword('$2y$17$Gpo2Jg7yC1OoeDNtg.HClOXoYkHr6CSUukX061qEMkeNkpOGfWXkq')
                ->setFirstname('Thomas')
                ->setLastname('BOYER')
                ->setLevel($LevelRepository->findOneBy(['name' => 'Administrateur']))
        );

        $UserRepository->save(
            (new User())
                ->setEmail('johndoe@gmail.com')
                ->setPassword('$2y$17$Gpo2Jg7yC1OoeDNtg.HClOXoYkHr6CSUukX061qEMkeNkpOGfWXkq')
                ->setFirstname('John')
                ->setLastname('DOE')
                ->setLevel($LevelRepository->findOneBy(['name' => 'Utilisateur']))
        );

        /**
         * Category
         */
        $CategoryRepository->save((new Category())->setName('Développement'));
        $CategoryRepository->save((new Category())->setName('Design'));
        $CategoryRepository->save((new Category())->setName('Marketing'));
        $CategoryRepository->save((new Category())->setName('Autre'));

        /**
         * BlogPost
         */
        $BlogPostRepository->save(
            (new BlogPost())
                ->setTitle('Mon premier article')
                ->setPost('Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae quod adipisci eligendi ducimus vero deleniti quisquam, quia saepe itaque officia natus voluptas excepturi neque hic culpa vitae veniam minima. Iusto.
                Praesentium fugiat obcaecati ea quasi ab soluta nemo exercitationem magnam consequatur quia numquam, accusantium quo, laboriosam incidunt quidem quae recusandae sunt voluptate minima quaerat laborum esse reprehenderit? Dolore, incidunt magni.
                Voluptate neque commodi autem reiciendis perspiciatis ad magni quod aperiam possimus voluptas eligendi aspernatur cupiditate error quam, expedita porro, veritatis est incidunt doloribus reprehenderit! Maxime cupiditate quidem ipsum saepe ipsam!
                Harum voluptatibus nostrum aut recusandae temporibus magni voluptatum non. Deserunt, expedita dolore corporis voluptates ipsa atque minus nulla sed aperiam. Quasi at soluta praesentium enim reiciendis consequuntur! Facere, ipsa hic.
                Deserunt voluptate voluptates quisquam. Impedit, aliquid ab atque perferendis accusamus rem ea delectus amet, quam quia minima mollitia ut exercitationem laudantium commodi molestiae accusantium sed hic, consectetur dolorum libero porro!')
                ->setCategory($CategoryRepository->findOneBy(['name' => 'Développement']))
                ->setUser($UserRepository->findOneBy(['email' => 'thomas@tydoo.fr']))
        );

        $BlogPostRepository->save(
            (new BlogPost())
                ->setTitle('Mon deuxième article')
                ->setPost('Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae quod adipisci eligendi ducimus vero deleniti quisquam, quia saepe itaque officia natus voluptas excepturi neque hic culpa vitae veniam minima. Iusto.
                Praesentium fugiat obcaecati ea quasi ab soluta nemo exercitationem magnam consequatur quia numquam, accusantium quo, laboriosam incidunt quidem quae recusandae sunt voluptate minima quaerat laborum esse reprehenderit? Dolore, incidunt magni.
                Voluptate neque commodi autem reiciendis perspiciatis ad magni quod aperiam possimus voluptas eligendi aspernatur cupiditate error quam, expedita porro, veritatis est incidunt doloribus reprehenderit! Maxime cupiditate quidem ipsum saepe ipsam!
                Harum voluptatibus nostrum aut recusandae temporibus magni voluptatum non. Deserunt, expedita dolore corporis voluptates ipsa atque minus nulla sed aperiam. Quasi at soluta praesentium enim reiciendis consequuntur! Facere, ipsa hic.
                Deserunt voluptate voluptates quisquam. Impedit, aliquid ab atque perferendis accusamus rem ea delectus amet, quam quia minima mollitia ut exercitationem laudantium commodi molestiae accusantium sed hic, consectetur dolorum libero porro!')
                ->setCategory($CategoryRepository->findOneBy(['name' => 'Design']))
                ->setUser($UserRepository->findOneBy(['email' => 'thomas@tydoo.fr']))
        );

        $BlogPostRepository->save(
            (new BlogPost())
                ->setTitle('Mon troisième article')
                ->setPost('Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae quod adipisci eligendi ducimus vero deleniti quisquam, quia saepe itaque officia natus voluptas excepturi neque hic culpa vitae veniam minima. Iusto.
                Praesentium fugiat obcaecati ea quasi ab soluta nemo exercitationem magnam consequatur quia numquam, accusantium quo, laboriosam incidunt quidem quae recusandae sunt voluptate minima quaerat laborum esse reprehenderit? Dolore, incidunt magni.
                Voluptate neque commodi autem reiciendis perspiciatis ad magni quod aperiam possimus voluptas eligendi aspernatur cupiditate error quam, expedita porro, veritatis est incidunt doloribus reprehenderit! Maxime cupiditate quidem ipsum saepe ipsam!
                Harum voluptatibus nostrum aut recusandae temporibus magni voluptatum non. Deserunt, expedita dolore corporis voluptates ipsa atque minus nulla sed aperiam. Quasi at soluta praesentium enim reiciendis consequuntur! Facere, ipsa hic.
                Deserunt voluptate voluptates quisquam. Impedit, aliquid ab atque perferendis accusamus rem ea delectus amet, quam quia minima mollitia ut exercitationem laudantium commodi molestiae accusantium sed hic, consectetur dolorum libero porro!')
                ->setCategory($CategoryRepository->findOneBy(['name' => 'Marketing']))
                ->setUser($UserRepository->findOneBy(['email' => 'thomas@tydoo.fr']))
        );

        $BlogPostRepository->save(
            (new BlogPost())
                ->setTitle('Mon quatrième article')
                ->setPost('Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae quod adipisci eligendi ducimus vero deleniti quisquam, quia saepe itaque officia natus voluptas excepturi neque hic culpa vitae veniam minima. Iusto.
                Praesentium fugiat obcaecati ea quasi ab soluta nemo exercitationem magnam consequatur quia numquam, accusantium quo, laboriosam incidunt quidem quae recusandae sunt voluptate minima quaerat laborum esse reprehenderit? Dolore, incidunt magni.
                Voluptate neque commodi autem reiciendis perspiciatis ad magni quod aperiam possimus voluptas eligendi aspernatur cupiditate error quam, expedita porro, veritatis est incidunt doloribus reprehenderit! Maxime cupiditate quidem ipsum saepe ipsam!
                Harum voluptatibus nostrum aut recusandae temporibus magni voluptatum non. Deserunt, expedita dolore corporis voluptates ipsa atque minus nulla sed aperiam. Quasi at soluta praesentium enim reiciendis consequuntur! Facere, ipsa hic.
                Deserunt voluptate voluptates quisquam. Impedit, aliquid ab atque perferendis accusamus rem ea delectus amet, quam quia minima mollitia ut exercitationem laudantium commodi molestiae accusantium sed hic, consectetur dolorum libero porro!')
                ->setCategory($CategoryRepository->findOneBy(['name' => 'Autre']))
                ->setUser($UserRepository->findOneBy(['email' => 'thomas@tydoo.fr']))
        );

        /**
         * Comment
         */
        $CommentRepository->save(
            (new Comment())
                ->setComment('Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae quod adipisci eligendi ducimus vero deleniti quisquam, quia saepe itaque officia natus voluptas excepturi neque hic culpa vitae veniam minima. Iusto.
                Praesentium fugiat obcaecati ea quasi ab soluta nemo exercitationem magnam consequatur quia numquam, accusantium quo, laboriosam incidunt quidem quae recusandae sunt voluptate minima quaerat laborum esse reprehenderit? Dolore, incidunt magni.
                Voluptate neque commodi autem reiciendis perspiciatis ad')
                ->setBlogPost($BlogPostRepository->findOneBy(['title' => 'Mon premier article']))
                ->setUser($UserRepository->findOneBy(['email' => 'johndoe@gmail.com']))
                ->setValidated(true)
        );

        $CommentRepository->save(
            (new Comment())
                ->setComment('Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae quod adipisci eligendi ducimus vero deleniti quisquam, quia saepe itaque officia natus voluptas excepturi neque hic culpa vitae veniam minima. Iusto.
                Praesentium fugiat obcaecati ea quasi ab soluta nemo exercitationem magnam consequatur quia numquam, accusantium quo, laboriosam incidunt quidem quae recusandae sunt voluptate minima quaerat laborum esse reprehenderit? Dolore, incidunt magni.
                Voluptate neque commodi autem reiciendis perspiciatis ad')
                ->setBlogPost($BlogPostRepository->findOneBy(['title' => 'Mon premier article']))
                ->setUser($UserRepository->findOneBy(['email' => 'johndoe@gmail.com']))
                ->setValidated(true)
        );

        $CommentRepository->save(
            (new Comment())
                ->setComment('Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae quod adipisci eligendi ducimus vero deleniti quisquam, quia saepe itaque officia natus voluptas excepturi neque hic culpa vitae veniam minima. Iusto.
                Praesentium fugiat obcaecati ea quasi ab')
                ->setBlogPost($BlogPostRepository->findOneBy(['title' => 'Mon deuxième article']))
                ->setUser($UserRepository->findOneBy(['email' => 'johndoe@gmail.com']))
                ->setValidated(true)
        );

        echo "Données de test créées !\n";
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

new Fixtures();
