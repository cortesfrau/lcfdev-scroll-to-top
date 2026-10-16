<?php

declare(strict_types=1);

namespace LcfDev\WpScrollToTop;

use LcfDev\WpScrollToTop\Assets\Enqueue;

final class Plugin
{
    private static ?self $instance = null;

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function run(): void
    {
        (new Enqueue())->register();
        (new Button())->register();
        (new Settings())->register();
    }
}
