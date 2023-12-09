<?php

namespace App\Controller;

use App\Response;
use App\Route;

class ErrorController {

    #[Route(path: '/_error/500', name: 'error.500')]
    public function internalServerError(): Response {
        return new Response('error/500.html.twig', [], 500);
    }

    #[Route(path: '/_error/404', name: 'error.404')]
    public function notFound(): Response {
        return new Response('error/404.html.twig', [], 404);
    }

    #[Route(path: '/_error/403', name: 'error.403')]
    public function forbidden(): Response {
        return new Response('error/403.html.twig', [], 403);
    }
}
