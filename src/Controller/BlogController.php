<?php

namespace App\Controller;

use Core\Attribute\Route;
use Core\Response\Response;

class BlogController {

    #[Route(path: '/blog', name: 'blog')]
    public function blog(): Response {
        return new Response('blog/liste.html.twig');
    }

    #[Route(path: '/blog/{id}', name: 'blog_show')]
    public function show(int $id): Response {
        return new Response('blog/blog_show.html.twig', ['id' => $id]);
    }
}
