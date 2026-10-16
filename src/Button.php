<?php

declare(strict_types=1);

namespace LcfDev\WpScrollToTop;

final class Button
{
    public function register(): void
    {
        add_action('wp_footer', [$this, 'render']);
    }

    public function render(): void
    {
        $colors = Settings::colors();

        printf(
            '<div id="lcfdev-scroll-to-top" style="%1$s">
                <button type="button" aria-label="%2$s">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up" aria-hidden="true" focusable="false"><path d="m18 15-6-6-6 6"/></svg>
                </button>
            </div>',
            esc_attr(sprintf(
                '--lcfdev-scroll-to-top-background:%s;--lcfdev-scroll-to-top-icon:%s;',
                $colors['background_color'],
                $colors['icon_color']
            )),
            esc_attr__('Scroll to top', 'lcfdev-scroll-to-top')
        );
    }
}
