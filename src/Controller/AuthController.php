<?php

namespace App\Controller;

use Core\Security;
use App\Entity\User;
use App\Repository\LevelRepository;
use Core\Attribute\Route;
use Core\Response\Response;
use Core\AbstractController;
use App\Repository\UserRepository;
use Core\Attribute\isGranted;
use Core\Response\RedirectResponse;

class AuthController extends AbstractController {

    private readonly Security $security;
    private readonly UserRepository $userReposiotry;
    private readonly LevelRepository $levelRepository;

    public function __construct() {
        $this->security = new Security();
        $this->userReposiotry = new UserRepository();
        $this->levelRepository = new LevelRepository();
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(): Response {
        if ($this->security->isLogged) {
            return new RedirectResponse('home');
        }

        $error = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->security->isCsrfTokenValid('login', $_POST['_csrf_token'])) {
                $error = true;
            } else {
                $username = filter_var(htmlspecialchars(strip_tags(trim(addslashes($_POST['username'])))), FILTER_SANITIZE_EMAIL);
                if (!$username || !$this->checkEmail($username)) {
                    $error = true;
                } else {
                    $user = $this->userReposiotry->findOneBy(['email' => $username]);
                    if (!$user) {
                        $error = true;
                    } else {
                        if (!password_verify($_POST['password'], $user->getPassword())) {
                            $error = true;
                        } else {
                            session_regenerate_id();
                            $this->security->addSession('user', $user->getId());
                            $this->security->removeSession('login');
                            return new RedirectResponse('home');
                        }
                    }
                }
            }
        }

        return new Response(
            'auth/login.html.twig',
            [
                'error' => $error,
                '_csrf_token' => $this->security->generateToken('login')
            ]
        );
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response {
        session_destroy();
        session_regenerate_id();
        return new RedirectResponse('home');
    }

    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(): Response {
        if ($this->security->isLogged) {
            return new RedirectResponse('home');
        }

        $error = false;
        $message = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->security->isCsrfTokenValid('register', $_POST['_csrf_token'])) {
                $error = 1;
                $message = "Une erreur est survenue, veuillez réessayer.";
            } else {
                $username = filter_var(htmlspecialchars(strip_tags(trim(addslashes($_POST['username'])))), FILTER_SANITIZE_EMAIL);
                if (!$username || !$this->checkEmail($username)) {
                    $error = 2;
                    $message = "L'adresse email n'est pas valide.";
                } else {
                    $user = $this->userReposiotry->findOneBy(['email' => $username]);
                    if ($user) {
                        $error = 2;
                        $message = "L'adresse email est déjà utilisée.";
                    } else {
                        if (!$this->checkPassword($_POST['password'])) {
                            $error = 3;
                            $message = "Le mot de passe doit contenir au moins 12 caractères, dont au moins un chiffre et un caractère spécial.";
                        } else {
                            if ($_POST['firstname'] === '' || $_POST['lastname'] === '') {
                                $error = 4;
                                $message = "Veuillez renseigner votre nom et prénom.";
                            } else {
                                $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 17]);
                                $user = new User();
                                $user
                                    ->setFirstname(htmlspecialchars(strip_tags(trim(addslashes($_POST['firstname'])))))
                                    ->setLastname(htmlspecialchars(strip_tags(trim(addslashes($_POST['lastname'])))))
                                    ->setEmail($username)
                                    ->setPassword($password)
                                    ->setLevel($this->levelRepository->findOneBy(['name' => 'Utilisateur']));
                                $this->userReposiotry->save($user);
                                session_regenerate_id();
                                $this->security->addSession(
                                    'user',
                                    $this->userReposiotry->findOneBy(['email' => $username])->getId()
                                );
                                $this->security->removeSession('register');
                                return new RedirectResponse('register_done');
                            }
                        }
                    }
                }
            }
        }

        return new Response(
            'auth/register.html.twig',
            [
                'error' => $error,
                'message' => $message,
                '_csrf_token' => $this->security->generateToken('register')
            ]
        );
    }

    #[isGranted('Utilisateur')]
    #[Route('/register/done', name: 'register_done', methods: ['GET'])]
    public function register_done(): Response {
        return new Response('auth/register_done.html.twig');
    }

    private function checkEmail($email): bool {
        $pattern = '/^\S+@\S+\.\S+$/';

        if (preg_match($pattern, $email)) {
            return true;
        } else {
            return false;
        }
    }

    private function checkPassword($password): bool {
        $pattern = '/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{12,}$/';

        if (preg_match($pattern, $password)) {
            return true;
        } else {
            return false;
        }
    }
}
