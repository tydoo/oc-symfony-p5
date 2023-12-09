<?php

namespace App;

use Attribute;

#[Attribute]
class Route {

    private string $controller;
    private string $action;

    public function __construct(
        private string $path,
        private string $name,
        private array $methods = ['GET']
    ) {
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getMethods(): array {
        return $this->methods;
    }

    public function getController(): string {
        return $this->controller;
    }

    public function setController(string $controller): self {
        $this->controller = $controller;

        return $this;
    }

    public function getAction(): string {
        return $this->action;
    }

    public function setAction(string $action): self {
        $this->action = $action;

        return $this;
    }
}
