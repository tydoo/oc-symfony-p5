<?php

namespace Core\Twig;

use Twig\TwigFilter;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class MarkdownExtension extends \Twig\Extension\AbstractExtension {
    public function getFilters(): array {
        return [
            new TwigFilter('markdown', [$this, 'markdown'], ['is_safe' => ['html']])
        ];
    }

    public function markdown(string $content): string {
        $converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => true,
        ]);

        return $converter->convert($content);
    }
}
