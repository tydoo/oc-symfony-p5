<?php

namespace Core;

class AbstractController {
    public function createNotFoundException(string $message): void {
        throw new \Exception($message, '404');
    }
}
