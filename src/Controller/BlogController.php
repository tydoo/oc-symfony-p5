<?php

namespace App\Controller;

use Exception;
use Core\Security;
use App\Entity\Comment;
use Core\Attribute\Route;
use Core\Response\Response;
use App\Repository\CommentRepository;
use App\Repository\BlogPostRepository;
use App\Repository\CategoryRepository;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class BlogController {

    private readonly BlogPostRepository $blogPostRepository;
    private readonly CommentRepository $commentRepository;
    private readonly Security $security;

    public function __construct() {
        $this->blogPostRepository = new BlogPostRepository();
        $this->commentRepository = new CommentRepository();
        $this->security = new Security();
    }

    #[Route(path: '/article/{id}', name: 'article', methods: ['GET', 'POST'])]
    public function article(int $id): Response {
        $article = $this->blogPostRepository->find($id);
        if (!$article) {
            throw new Exception('Article non trouvé !', '404');
        }

        $converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => true,
        ]);

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->security->isCsrfTokenValid('comments_create', $_POST['_csrf_token'])) {
                if ($_POST['message'] !== '') {
                    $comment = new Comment();
                    $comment
                        ->setBlogPost($article)
                        ->setComment(htmlspecialchars(strip_tags(trim(addslashes($_POST['message'])))))
                        ->setUser($this->security->user)
                        ->setValidated($this->security->isGranted('Administrateur') ? true : false);
                    $this->commentRepository->save($comment);
                    $this->security->removeSession('comments_create');
                    $message = 'Votre commentaire a bien été envoyé.';
                    if ($this->security->isGranted('Administrateur')) {
                        $message .= ' Il est visible directement.';
                    } else {
                        $message .= ' Il sera visible après validation par un administrateur.';
                    }
                }
            }
        }

        return new Response('blog/article.html.twig', [
            'article' => $article,
            'post' => $converter->convert($article->getPost()),
            'comments' => $this->commentRepository->findBy(['blog_post_id' => $article->getId()]),
            '_csrf_token' => $this->security->generateToken('comments_create'),
            'message' => $message
        ]);
    }

    #[Route(path: '/category/{id}', name: 'category', methods: ['GET'])]
    public function category(int $id): Response {
        $categoryRepository = new CategoryRepository();
        $blogPostRepository = new BlogPostRepository();

        $category = $categoryRepository->find($id);

        if (!$category) {
            throw new Exception('Catégorie non trouvé !', '404');
        } else {
            $articles = $blogPostRepository->findBy(['category_id' => $category->getId()]);

            $comments = [];
            foreach ($articles as $key => $value) {
                $comments[$key] = $this->commentRepository->countBy(['validated' => true, 'blog_post_id' => $value->getId()]);
            }

            return new Response('blog/category.html.twig', [
                'category' => $category,
                'blogPosts' => $articles,
                'comments' => $comments
            ]);
        }
    }
}
