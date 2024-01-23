<?php

namespace App\Controller;

use Core\Attribute\Route;
use Core\Response\Response;
use Core\AbstractController;
use App\Repository\BlogPostRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class BlogController extends AbstractController {

    private readonly BlogPostRepository $blogPostRepository;
    private readonly CommentRepository $commentRepository;

    public function __construct() {
        $this->blogPostRepository = new BlogPostRepository();
        $this->commentRepository = new CommentRepository();
    }

    #[Route(path: '/article/{id}', name: 'article')]
    public function article(int $id): Response {
        $article = $this->blogPostRepository->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => true,
        ]);

        return new Response('blog/article.html.twig', [
            'article' => $article,
            'post' => $converter->convert($article->getPost()),
            'comments' => $this->commentRepository->findBy(['blog_post_id' => $article->getId()]),
        ]);
    }

    #[Route(path: '/category/{id}', name: 'category', methods: ['GET'])]
    public function category(int $id): Response {
        $categoryRepository = new CategoryRepository();
        $blogPostRepository = new BlogPostRepository();

        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
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
