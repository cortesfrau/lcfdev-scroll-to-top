<?php

declare(strict_types=1);

namespace LcfDev\WpScrollToTop;

final class Settings
{
    public const OPTION = 'lcfdev_scroll_to_top';

    private const PAGE = 'lcfdev-scroll-to-top';

    private const GROUP = 'lcfdev-scroll-to-top';

    private const SECTION = 'lcfdev_scroll_to_top_colors';

    public function register(): void
    {
        add_action('admin_menu', [$this, 'addPage']);
        add_action('admin_init', [$this, 'registerSetting']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
    }

    public function addPage(): void
    {
        add_options_page(
            __('Scroll to Top', 'lcfdev-scroll-to-top'),
            __('Scroll to Top', 'lcfdev-scroll-to-top'),
            'manage_options',
            self::PAGE,
            [$this, 'renderPage']
        );
    }

    public function registerSetting(): void
    {
        register_setting(self::GROUP, self::OPTION, [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize'],
            'default' => self::defaults(),
        ]);

        add_settings_section(
            self::SECTION,
            __('Colors', 'lcfdev-scroll-to-top'),
            [$this, 'renderSection'],
            self::PAGE
        );

        $this->addColorField('background_color', __('Background color', 'lcfdev-scroll-to-top'));
        $this->addColorField('icon_color', __('Icon color', 'lcfdev-scroll-to-top'));
    }

    public function renderSection(): void
    {
        echo '<p>' . esc_html__('Choose the colors of the scroll to top button.', 'lcfdev-scroll-to-top') . '</p>';
    }

    /**
     * @param array{key: string} $args
     */
    public function renderColorField(array $args): void
    {
        $key = $args['key'];
        $value = self::colors()[$key];

        printf(
            '<input type="text" class="lcfdev-scroll-to-top-color" id="%1$s" name="%2$s[%1$s]" value="%3$s" data-default-color="%4$s">',
            esc_attr($key),
            esc_attr(self::OPTION),
            esc_attr($value),
            esc_attr(self::defaults()[$key])
        );
    }

    public function renderPage(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        echo '<div class="wrap">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
        settings_errors();
        echo '<form action="options.php" method="post">';
        settings_fields(self::GROUP);
        do_settings_sections(self::PAGE);
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    /**
     * @param mixed $input
     * @return array{background_color: string, icon_color: string}
     */
    public function sanitize($input): array
    {
        $defaults = self::defaults();
        $input = is_array($input) ? $input : [];

        return [
            'background_color' => $this->color($input['background_color'] ?? null, $defaults['background_color']),
            'icon_color' => $this->color($input['icon_color'] ?? null, $defaults['icon_color']),
        ];
    }

    public function enqueueAssets(string $hook): void
    {
        if ($hook !== 'settings_page_' . self::PAGE) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_add_inline_script(
            'wp-color-picker',
            'jQuery(function ($) { $(".lcfdev-scroll-to-top-color").wpColorPicker(); });'
        );
    }

    /**
     * @return array{background_color: string, icon_color: string}
     */
    public static function defaults(): array
    {
        return [
            'background_color' => '#000000',
            'icon_color' => '#ffffff',
        ];
    }

    private function addColorField(string $key, string $label): void
    {
        add_settings_field(
            $key,
            $label,
            [$this, 'renderColorField'],
            self::PAGE,
            self::SECTION,
            [
                'key' => $key,
            ]
        );
    }

    /**
     * @return array{background_color: string, icon_color: string}
     */
    public static function colors(): array
    {
        $stored = get_option(self::OPTION, self::defaults());

        return (new self())->sanitize(is_array($stored) ? $stored : []);
    }

    private function color(mixed $value, string $fallback): string
    {
        if (! is_string($value)) {
            return $fallback;
        }

        $sanitized = sanitize_hex_color($value);

        return is_string($sanitized) && $sanitized !== '' ? $sanitized : $fallback;
    }
}
