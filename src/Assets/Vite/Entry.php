<?php

declare(strict_types=1);

namespace LcfDev\WpScrollToTop\Assets\Vite;

final class Entry
{
    /** @param list<string> $cssPaths Absolute paths, so the CSS can be printed inline. */
    public function __construct(
        public string $jsUrl,
        public array $cssPaths,
    ) {}
}
