<?php

namespace Core\Attribute;

use Attribute;

#[Attribute]
class isGranted {

    public function __construct(private string $nameLevel) {
    }

    public function getNameLevel(): string {
        return $this->nameLevel;
    }
}
