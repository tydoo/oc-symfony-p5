<?php

namespace App\Entity;

use Core\AbstractEntity;
use Core\Attribute\Entity;
use Core\BlogInterface\LevelInterface;
use App\Repository\LevelRepository;

#[Entity(table: 'level', repository: LevelRepository::class)]
class Level extends AbstractEntity implements LevelInterface {
    private ?int $id = null;
    private string $name;
    private ?string $heritage = null;

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

    /**
     * String séparé par des virgules
     */
    public function getHeritage(): ?string {
        return $this->heritage;
    }

    public function setHeritage(?string $heritage): self {
        $this->heritage = $heritage;
        return $this;
    }
}
