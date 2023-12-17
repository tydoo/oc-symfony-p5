<?php

namespace Core\Attribute;

use Attribute;

#[Attribute]
class Entity {

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
}
