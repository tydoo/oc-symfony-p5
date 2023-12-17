<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Core\AbstractEntity;
use Core\Attribute\Entity;

#[Entity(table: 'category', repository: CategoryRepository::class)]
class Category extends AbstractEntity {

    private ?int $id = null;
    private string $name;

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }
}
