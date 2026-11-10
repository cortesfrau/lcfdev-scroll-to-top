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

        // The CSS is a few hundred bytes for a button that starts hidden: printed inline it
        // saves a render-blocking request in the <head>.
        foreach ($resolved->cssPaths as $index => $cssPath) {
            $css = is_readable($cssPath) ? file_get_contents($cssPath) : false;
            if ($css === false || $css === '') {
                continue;
            }

            $handle = self::HANDLE . '-' . $index;
            wp_register_style($handle, false, [], null);
            wp_enqueue_style($handle);
            wp_add_inline_style($handle, $css);
        }

        // Before WordPress 6.3 the strategy is ignored and the array only means "in the footer".
        wp_enqueue_script(self::HANDLE, $resolved->jsUrl, [], null, [
            'in_footer' => true,
            'strategy'  => 'defer',
        ]);
    }
}
