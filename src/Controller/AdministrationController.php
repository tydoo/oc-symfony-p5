<?php

namespace App\Controller;

use Core\Security;
use App\Entity\User;
use Core\Attribute\Route;
use Core\Response\Response;
use Core\AbstractController;
use Core\Attribute\isGranted;
use App\Repository\UserRepository;
use App\Repository\LevelRepository;
use Core\Response\RedirectResponse;
use App\Repository\CommentRepository;
use App\Repository\BlogPostRepository;

class AdministrationController extends AbstractController {

    private readonly Security $security;
    private readonly UserRepository $userRepository;
    private readonly LevelRepository $levelRepository;
    private readonly BlogPostRepository $blogPostRepository;
    private readonly CommentRepository $commentRepository;

    public function __construct() {
        $this->security = new Security();
        $this->userRepository = new UserRepository();
        $this->levelRepository = new LevelRepository();
        $this->blogPostRepository = new BlogPostRepository();
        $this->commentRepository = new CommentRepository();
    }

    #[isGranted('Administrateur')]
    #[Route('/administration/users', name: 'administration_users', methods: ['GET'])]
    public function users(): Response {
        $users = $this->userRepository->findAll();

        $blogsPost = [];
        $comments = [];
        foreach ($users as $key => $user) {
            $blogsPost[$key] = $this->blogPostRepository->countBy(['user_id' => $user->getId()]);
            $comments[$key] = $this->commentRepository->countBy(['user_id' => $user->getId()]);
        }

        return new Response(
            'administration/users.html.twig',
            [
                'users' => $users,
                'blogsPost' => $blogsPost,
                'comments' => $comments,
            ]
        );
    }

    #[isGranted('Administrateur')]
    #[Route('/administration/users/{id}', name: 'administration_users_edit', methods: ['GET', 'POST'])]
    public function users_edit(int $id): Response {
        $user = $this->userRepository->find($id);
        if ($user === null) {
            return new RedirectResponse('administration_users');
        }

        $error = false;
        $message = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->security->isCsrfTokenValid('users_edit', $_POST['_csrf_token'])) {
                $error = 1;
                $message = "Une erreur est survenue, veuillez réessayer.";
            } else {
                $username = filter_var(htmlspecialchars(strip_tags(trim(addslashes($_POST['username'])))), FILTER_SANITIZE_EMAIL);
                if (!$username || !$this->checkEmail($username)) {
                    $error = 2;
                    $message = "L'adresse email n'est pas valide.";
                } else {
                    if ($_POST['firstname'] === '' || $_POST['lastname'] === '') {
                        $error = 4;
                        $message = "Veuillez renseigner votre nom et prénom.";
                    } else {
                        $user
                            ->setFirstname(htmlspecialchars(strip_tags(trim(addslashes($_POST['firstname'])))))
                            ->setLastname(htmlspecialchars(strip_tags(trim(addslashes($_POST['lastname'])))))
                            ->setEmail($username)
                            ->setLevel($this->levelRepository->findOneBy(['name' => htmlspecialchars(strip_tags(trim(addslashes($_POST['level']))))]));
                        $this->userRepository->save($user);
                        $this->security->removeSession('users_edit');
                        return new RedirectResponse('administration_users');
                    }
                }
            }
        }

        return new Response(
            'administration/user_edit.html.twig',
            [
                'user' => $user,
                'error' => $error,
                'message' => $message,
                '_csrf_token' => $this->security->generateToken('users_edit'),
            ]
        );
    }

    #[isGranted('Administrateur')]
    #[Route('/administration/users/{id}/remove', name: 'administration_users_remove', methods: ['GET'])]
    public function users_remove(int $id): RedirectResponse {
        $user = $this->userRepository->find($id);
        if ($user !== null) {
            $this->userRepository->delete($user);
        }
        return new RedirectResponse('administration_users');
    }

    private function checkEmail($email): bool {
        $pattern = '/^\S+@\S+\.\S+$/';

        if (preg_match($pattern, $email)) {
            return true;
        } else {
            return false;
        }
    }
}
