<?php

declare(strict_types=1);

namespace LcfDev\WpScrollToTop\Assets\Vite;

final class Entry
{
    /** @param list<string> $cssUrls */
    public function __construct(
        public string $jsUrl,
        public array $cssUrls,
    ) {}
}
