<?php

namespace App\Entity;

use DateTimeImmutable;
use Core\AbstractEntity;
use Core\Attribute\Entity;
use Core\BlogInterface\UserInterface;
use App\Repository\UserRepository;

#[Entity(table: 'user', repository: UserRepository::class)]
class User extends AbstractEntity implements UserInterface {
    private ?int $id = null;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private DateTimeImmutable $createdAt;
    private Level $level;

    public function __construct() {
        $this->setCreatedAt(new DateTimeImmutable());
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getLevel(): Level {
        return $this->level;
    }

    public function setLevel(Level $level): self {
        $this->level = $level;
        return $this;
    }
}
