<?php

namespace Core\Twig;

use Core\Security;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class IsGrantedExtension extends AbstractExtension {

    public function getFunctions(): array {
        return [
            new TwigFunction('is_granted', [$this, 'isGranted']),
        ];
    }

    public function isGranted(?string $levelName): bool {
        $security = new Security();
        if (!$security->isLogged) {
            return false;
        }

        return $security->isGranted($levelName);
    }
}
