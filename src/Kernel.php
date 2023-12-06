<?php

namespace App;

class Kernel {

    private Router $router;

    public function run() {
        $this->router = new Router();
        $this->router->run($_SERVER['REQUEST_URI']);
    }
}
