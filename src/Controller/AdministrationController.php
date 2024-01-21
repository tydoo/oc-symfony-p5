<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Core\Security;
use App\Entity\Category;
use Core\Attribute\Route;
use Core\Response\Response;
use Core\AbstractController;
use Core\Attribute\isGranted;
use App\Repository\UserRepository;
use App\Repository\LevelRepository;
use Core\Response\RedirectResponse;
use App\Repository\CommentRepository;
use App\Repository\BlogPostRepository;
use App\Repository\CategoryRepository;

class AdministrationController extends AbstractController {

    private readonly Security $security;
    private readonly UserRepository $userRepository;
    private readonly LevelRepository $levelRepository;
    private readonly BlogPostRepository $blogPostRepository;
    private readonly CommentRepository $commentRepository;
    private readonly CategoryRepository $categoryRepository;

    public function __construct() {
        $this->security = new Security();
        $this->userRepository = new UserRepository();
        $this->levelRepository = new LevelRepository();
        $this->blogPostRepository = new BlogPostRepository();
        $this->commentRepository = new CommentRepository();
        $this->categoryRepository = new CategoryRepository();
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

    #[isGranted('Administrateur')]
    #[Route('/administration/categories', name: 'administration_categories', methods: ['GET'])]
    public function categories(): Response {
        $categories = $this->categoryRepository->findAll();

        $blogsPost = [];
        foreach ($categories as $key => $category) {
            $blogsPost[$key] = $this->blogPostRepository->countBy(['category_id' => $category->getId()]);
        }

        return new Response(
            'administration/categories.html.twig',
            [
                'categories' => $categories,
                'blogsPost' => $blogsPost,
            ]
        );
    }

    #[isGranted('Administrateur')]
    #[Route('/administration/categories/create', name: 'administration_categories_create', methods: ['GET', 'POST'])]
    public function categorie_create(): Response {
        $error = false;
        $message = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->security->isCsrfTokenValid('categories_create', $_POST['_csrf_token'])) {
                $error = 1;
                $message = "Une erreur est survenue, veuillez réessayer.";
            } else {
                $pattern = '/^[a-zA-ZÀ-ÿ][a-zA-Z0-9-_ À-ÿ]*$/';
                $categoryName = htmlspecialchars(strip_tags(trim(addslashes($_POST['name']))));
                $categoryCheck = $this->categoryRepository->findOneBy(['name' => $categoryName]);

                if (
                    $_POST['name'] === '' ||
                    !preg_match($pattern, $categoryName) ||
                    strlen($categoryName) < 3 ||
                    $categoryCheck !== null
                ) {
                    $error = 4;
                    $message = "Le nom de la catégorie n'est pas valide.";
                } else {
                    $category = new Category();
                    $category
                        ->setName($categoryName);
                    $this->categoryRepository->save($category);
                    $this->security->removeSession('categories_create');
                    return new RedirectResponse('administration_categories');
                }
            }
        }

        return new Response(
            'administration/categorie_create.html.twig',
            [
                'error' => $error,
                'message' => $message,
                '_csrf_token' => $this->security->generateToken('categories_create'),
            ]
        );
    }

    #[isGranted('Administrateur')]
    #[Route('/administration/categories/{id}', name: 'administration_categories_edit', methods: ['GET', 'POST'])]
    public function categorie_edit(int $id): Response {
        $category = $this->categoryRepository->find($id);
        if ($category === null) {
            return new RedirectResponse('administration_categories');
        }

        $error = false;
        $message = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->security->isCsrfTokenValid('categories_edit', $_POST['_csrf_token'])) {
                $error = 1;
                $message = "Une erreur est survenue, veuillez réessayer.";
            } else {
                $pattern = '/^[a-zA-ZÀ-ÿ][a-zA-Z0-9-_ À-ÿ]*$/';
                $categoryName = htmlspecialchars(strip_tags(trim(addslashes($_POST['name']))));
                $categoryCheck = $this->categoryRepository->findOneBy(['name' => $categoryName]);
                if (
                    $_POST['name'] === '' ||
                    !preg_match($pattern, $categoryName) ||
                    strlen($categoryName) < 3 ||
                    $categoryCheck !== null
                ) {
                    $error = 4;
                    $message = "Le nom de la catégorie n'est pas valide.";
                } else {
                    $category
                        ->setName($categoryName);
                    $this->categoryRepository->save($category);
                    $this->security->removeSession('categories_create');
                    return new RedirectResponse('administration_categories');
                }
            }
        }


        return new Response(
            'administration/categorie_edit.html.twig',
            [
                'error' => $error,
                'message' => $message,
                'category' => $category,
                '_csrf_token' => $this->security->generateToken('categories_edit'),
            ]
        );
    }

    #[isGranted('Administrateur')]
    #[Route('/administration/categories/{id}/remove', name: 'administration_categories_remove', methods: ['GET'])]
    public function categories_remove(int $id): RedirectResponse {
        $category = $this->categoryRepository->find($id);
        if ($category !== null) {
            $this->categoryRepository->delete($category);
        }
        return new RedirectResponse('administration_categories');
    }

    #[isGranted('Administrateur')]
    #[Route('/administration/articles', name: 'administration_articles', methods: ['GET'])]
    public function articles(): Response {
        $articles = $this->blogPostRepository->findAll();

        $comments = [];
        foreach ($articles as $key => $article) {
            $comments[$key] = $this->commentRepository->countBy(['blog_post_id' => $article->getId()]);
        }

        return new Response(
            'administration/articles.html.twig',
            [
                'articles' => $articles,
                'comments' => $comments,
            ]
        );
    }

    #[isGranted('Administrateur')]
    #[Route('/administration/articles/create', name: 'administration_articles_create', methods: ['GET', 'POST'])]
    public function articles_create(): Response {
        $error = false;
        $message = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->security->isCsrfTokenValid('articles_create', $_POST['_csrf_token'])) {
                $error = 1;
                $message = "Une erreur est survenue, veuillez réessayer.";
            } else {
                $pattern = '/^[a-zA-ZÀ-ÿ][a-zA-Z0-9-_ À-ÿ.,;:!?"\'()]*$/';
                $title = htmlspecialchars(strip_tags(trim(addslashes($_POST['name']))));
                if (
                    $_POST['name'] === '' ||
                    !preg_match($pattern, $title) ||
                    strlen($title) < 3
                ) {
                    $error = 4;
                    $message = "Le titre de l'article n'est pas valide.";
                } else {
                    $article = new BlogPost();
                    $article
                        ->setTitle($title)
                        ->setCategory($this->categoryRepository->find($_POST['category']))
                        ->setPost(htmlspecialchars(strip_tags(trim(addslashes($_POST['message'])))))
                        ->setUser($this->security->user);
                    $this->blogPostRepository->save($article);
                    $this->security->removeSession('articles_create');
                    return new RedirectResponse('administration_articles');
                }
            }
        }

        return new Response(
            'administration/articles_create.html.twig',
            [
                'error' => $error,
                'message' => $message,
                'categories' => $this->categoryRepository->findAll(),
                '_csrf_token' => $this->security->generateToken('articles_create'),
            ]
        );
    }

    #[isGranted('Administrateur')]
    #[Route('/administration/articles/{id}', name: 'administration_articles_edit', methods: ['GET', 'POST'])]
    public function articles_edit(int $id): Response {
        $article = $this->blogPostRepository->find($id);
        if ($article === null) {
            return new RedirectResponse('administration_articles');
        }

        $error = false;
        $message = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->security->isCsrfTokenValid('articles_edit', $_POST['_csrf_token'])) {
                $error = 1;
                $message = "Une erreur est survenue, veuillez réessayer.";
            } else {
                $pattern = '/^[a-zA-ZÀ-ÿ][a-zA-Z0-9-_ À-ÿ.,;:!?"\'()]*$/';
                $title = htmlspecialchars(strip_tags(trim(addslashes($_POST['name']))));
                if (
                    $_POST['name'] === '' ||
                    !preg_match($pattern, $title) ||
                    strlen($title) < 3
                ) {
                    $error = 4;
                    $message = "Le titre de l'article n'est pas valide.";
                } else {
                    $article
                        ->setTitle($title)
                        ->setCategory($this->categoryRepository->find($_POST['category']))
                        ->setPost(htmlspecialchars(strip_tags(trim(addslashes($_POST['message'])))))
                        ->setUser($this->security->user);
                    $this->blogPostRepository->save($article);
                    $this->security->removeSession('articles_edit');
                    return new RedirectResponse('administration_articles');
                }
            }
        }

        return new Response(
            'administration/articles_edit.html.twig',
            [
                'error' => $error,
                'message' => $message,
                'article' => $article,
                'categories' => $this->categoryRepository->findAll(),
                '_csrf_token' => $this->security->generateToken('articles_edit'),
            ]
        );
    }

    #[isGranted('Administrateur')]
    #[Route('/administration/articles/{id}/remove', name: 'administration_articles_remove', methods: ['GET'])]
    public function articles_remove(int $id): RedirectResponse {
        $article = $this->blogPostRepository->find($id);
        if ($article !== null) {
            $this->blogPostRepository->delete($article);
        }
        return new RedirectResponse('administration_articles');
    }
}
