<?php

namespace App\Controller;

use Core\Attribute\Route;
use Core\Response\Response;
use Core\AbstractController;
use App\Repository\BlogPostRepository;
use App\Repository\CategoryRepository;

class BlogController extends AbstractController {
    #[Route(path: '/blog/{id}', name: 'blog_show')]
    public function show(int $id): Response {
        return new Response('blog/blog_show.html.twig', ['id' => $id]);
    }

    #[Route(path: '/category/{id}', name: 'category_show', methods: ['GET'])]
    public function category(int $id): Response {
        $categoryRepository = new CategoryRepository();
        $blogPostRepository = new BlogPostRepository();

        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        } else {
            return new Response('blog/category_show.html.twig', [
                'category' => $category,
                'blogPosts' => $blogPostRepository->findBy(['category_id' => $category->getId()]), // 'category' => $category est équivalent à 'category_id' => $category->getId() car la propriété de l'entité BlogPost est 'category_id
            ]);
        }
    }
}
