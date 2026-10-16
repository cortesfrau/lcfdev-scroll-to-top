<?php

declare(strict_types=1);

namespace LcfDev\WpScrollToTop\Assets;

use LcfDev\WpScrollToTop\Assets\Vite\Manifest;

final class Enqueue
{
    private const ENTRY = 'resources/js/app.js';

    private const HANDLE = 'lcfdev-scroll-to-top';

    private Manifest $manifest;

    public function __construct(?Manifest $manifest = null)
    {
        $this->manifest = $manifest ?? new Manifest();
    }

    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void
    {
        $resolved = $this->manifest->entry(self::ENTRY);
        if ($resolved === null) {
            return;
        }

        foreach ($resolved->cssUrls as $index => $cssUrl) {
            wp_enqueue_style(self::HANDLE . '-' . $index, $cssUrl, [], null);
        }

        wp_enqueue_script(self::HANDLE, $resolved->jsUrl, [], null, true);
    }
}
