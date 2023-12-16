<?php

namespace App\Entity;

use DateTimeImmutable;
use Core\AbstractEntity;

class User extends AbstractEntity {
    private ?int $id = null;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private DateTimeImmutable $created_at;
    private Level $level;

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
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): self {
        $this->created_at = $created_at;
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
