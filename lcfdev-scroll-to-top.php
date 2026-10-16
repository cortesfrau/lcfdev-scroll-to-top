<?php

/**
 * Plugin Name:       Scroll to Top
 * Plugin URI:        https://github.com/cortesfrau/wp-scroll-to-top
 * Description:       Adds a button that scrolls the page back to the top.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Lluis Cortes
 * Author URI:        https://github.com/cortesfrau
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lcfdev-scroll-to-top
 */

defined('ABSPATH') || exit;

use LcfDev\WpScrollToTop\Plugin;

define('LCFDEV_SCROLL_TO_TOP_PATH', plugin_dir_path(__FILE__));
define('LCFDEV_SCROLL_TO_TOP_URL', plugin_dir_url(__FILE__));

require_once __DIR__ . '/vendor/autoload.php';

add_action('plugins_loaded', function () {
    Plugin::instance()->run();
});
