<?php

namespace App\Twig;

use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\BlogPostRepository;
use App\Repository\CategoryRepository;

class ArticleAuHasardExtension extends AbstractExtension implements GlobalsInterface {

    public function getGlobals(): array {
        return [
            'article_au_hasard' => $this->getArticleAuHasard(),
        ];
    }

    private function getArticleAuHasard(): int {
        $blogPostRepository = new BlogPostRepository();
        return $blogPostRepository->findRandom()->getId();
    }
}
