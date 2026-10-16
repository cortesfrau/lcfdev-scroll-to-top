<?php

declare(strict_types=1);

namespace LcfDev\WpScrollToTop;

final class Settings
{
    public const OPTION = 'lcfdev_scroll_to_top';

    private const PAGE = 'lcfdev-scroll-to-top';

    private const GROUP = 'lcfdev-scroll-to-top';

    private const COLORS_SECTION = 'lcfdev_scroll_to_top_colors';

    private const POSITION_SECTION = 'lcfdev_scroll_to_top_position';

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
            self::COLORS_SECTION,
            __('Colors', 'lcfdev-scroll-to-top'),
            [$this, 'renderColorsSection'],
            self::PAGE
        );

        $this->addColorField('background_color', __('Background color', 'lcfdev-scroll-to-top'));
        $this->addColorField('icon_color', __('Icon color', 'lcfdev-scroll-to-top'));

        add_settings_section(
            self::POSITION_SECTION,
            __('Position', 'lcfdev-scroll-to-top'),
            [$this, 'renderPositionSection'],
            self::PAGE
        );

        $this->addOffsetField('offset_right', __('Distance from the right', 'lcfdev-scroll-to-top'));
        $this->addOffsetField('offset_bottom', __('Distance from the bottom', 'lcfdev-scroll-to-top'));
    }

    public function renderColorsSection(): void
    {
        echo '<p>' . esc_html__('Choose the colors of the scroll to top button.', 'lcfdev-scroll-to-top') . '</p>';
    }

    public function renderPositionSection(): void
    {
        echo '<p>' . esc_html__('Set the distance from the right and bottom edges of the screen.', 'lcfdev-scroll-to-top') . '</p>';
    }

    /**
     * @param array{key: string} $args
     */
    public function renderColorField(array $args): void
    {
        $key = $args['key'];
        $value = self::values()[$key];

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
     * @return array{background_color: string, icon_color: string, offset_right: int, offset_bottom: int}
     */
    public function sanitize($input): array
    {
        $defaults = self::defaults();
        $input = is_array($input) ? $input : [];

        return [
            'background_color' => $this->color($input['background_color'] ?? null, $defaults['background_color']),
            'icon_color' => $this->color($input['icon_color'] ?? null, $defaults['icon_color']),
            'offset_right' => $this->offset($input['offset_right'] ?? null, $defaults['offset_right']),
            'offset_bottom' => $this->offset($input['offset_bottom'] ?? null, $defaults['offset_bottom']),
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
     * @return array{background_color: string, icon_color: string, offset_right: int, offset_bottom: int}
     */
    public static function defaults(): array
    {
        return [
            'background_color' => '#000000',
            'icon_color' => '#ffffff',
            'offset_right' => 24,
            'offset_bottom' => 24,
        ];
    }

    private function addColorField(string $key, string $label): void
    {
        add_settings_field(
            $key,
            $label,
            [$this, 'renderColorField'],
            self::PAGE,
            self::COLORS_SECTION,
            [
                'key' => $key,
            ]
        );
    }

    private function addOffsetField(string $key, string $label): void
    {
        add_settings_field(
            $key,
            $label,
            [$this, 'renderOffsetField'],
            self::PAGE,
            self::POSITION_SECTION,
            [
                'key' => $key,
            ]
        );
    }

    /**
     * @param array{key: string} $args
     */
    public function renderOffsetField(array $args): void
    {
        $key = $args['key'];
        $value = self::values()[$key];

        printf(
            '<input type="number" id="%1$s" name="%2$s[%1$s]" value="%3$d" min="0" step="1" class="small-text"> %4$s',
            esc_attr($key),
            esc_attr(self::OPTION),
            $value,
            esc_html__('px', 'lcfdev-scroll-to-top')
        );
    }

    /**
     * @return array{background_color: string, icon_color: string, offset_right: int, offset_bottom: int}
     */
    public static function values(): array
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

    private function offset(mixed $value, int $fallback): int
    {
        if (is_int($value)) {
            return $value >= 0 ? $value : $fallback;
        }

        if (is_string($value) && preg_match('/^\d+$/', $value) === 1) {
            return (int) $value;
        }

        return $fallback;
    }
}
