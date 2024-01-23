<?php

namespace App\Controller;

use Core\Attribute\Route;
use Core\Response\Response;
use Core\AbstractController;
use App\Repository\BlogPostRepository;
use App\Repository\CategoryRepository;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class BlogController extends AbstractController {

    private readonly BlogPostRepository $blogPostRepository;


    public function __construct() {
        $this->blogPostRepository = new BlogPostRepository();
    }

    #[Route(path: '/article/{id}', name: 'blog_show')]
    public function show(int $id): Response {
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
            'post' => $converter->convert($article->getPost())
        ]);
    }

    #[Route(path: '/category/{id}', name: 'category_show', methods: ['GET'])]
    public function category(int $id): Response {
        $categoryRepository = new CategoryRepository();
        $blogPostRepository = new BlogPostRepository();

        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        } else {
            return new Response('blog/category.html.twig', [
                'category' => $category,
                'blogPosts' => $blogPostRepository->findBy(['category_id' => $category->getId()]), // 'category' => $category est équivalent à 'category_id' => $category->getId() car la propriété de l'entité BlogPost est 'category_id
            ]);
        }
    }
}
