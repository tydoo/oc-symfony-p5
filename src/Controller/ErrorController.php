<?php

namespace App\Controller;

use App\Route;

class ErrorController extends AbstractController {

    #[Route(path: '/_error/500', name: 'error.500')]
    public function internalServerError() {
        return $this->response('error/500.html.twig', [], 500);
    }

    #[Route(path: '/_error/404', name: 'error.404')]
    public function notFound() {
        return $this->response('error/404.html.twig', [], 404);
    }

    #[Route(path: '/_error/403', name: 'error.403')]
    public function forbidden() {
        return $this->response('error/403.html.twig', [], 403);
    }
}
