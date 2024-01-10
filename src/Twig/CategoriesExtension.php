<?php

namespace App\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class CategoriesExtension extends AbstractExtension {

    public function getFunctions(): array {
        return [
            new TwigFunction('allCategories', [$this, 'getCategories']),
        ];
    }

    public function getCategories() {
        return (new CategoryRepository())->findAll();
    }
}
