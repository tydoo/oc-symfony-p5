<?php

namespace App\Twig;

use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class CategoriesExtension extends AbstractExtension implements GlobalsInterface {

    public function getGlobals(): array {
        return [
            'allCategories' => $this->getCategories(),
        ];
    }

    private function getCategories(): array {
        return (new CategoryRepository())->findAll();
    }
}
