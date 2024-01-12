<?php

namespace Core\Interface;

interface LevelInterface {
    public function getName(): string;

    /**
     * String séparé par des virgules
     */
    public function getHeritage(): ?string;
}
