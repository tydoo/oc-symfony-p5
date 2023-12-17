<?php

namespace Core\Attribute;

use Attribute;

#[Attribute]
class Entity {

    private string $class;

    public function __construct(
        private string $table,
        private string $repository
    ) {
    }

    public function getTable(): string {
        return $this->table;
    }

    public function getRepository(): string {
        return $this->repository;
    }

    public function getClass(): string {
        return $this->class;
    }

    public function setClass(string $class): void {
        $this->class = $class;
    }
}
