<?php
/**
 * Plugin Name:       Scroll to Top Button
 * Plugin URI:        https://github.com/jdyach/scroll-to-top-button
 * Description:       Injects a configurable floating "scroll to top" button on the front end. Customize the color, position, and scroll threshold via the Settings page.
 * Version:           1.0.0
 * Author:            Jacqui Dyachenko
 * Author URI:        https://jacquid.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       scroll-to-top-button
 * Requires at least: 6.0
 * Requires PHP:      8.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'STB_VERSION',    '1.0.0' );
define( 'STB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'STB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'STB_OPTIONS_KEY', 'stb_settings' );

require_once STB_PLUGIN_DIR . 'includes/class-stb-settings.php';
require_once STB_PLUGIN_DIR . 'includes/class-stb-output.php';

function stb_init(): void {
    STB_Settings::init();
    STB_Output::init();
}
add_action( 'plugins_loaded', 'stb_init' );

register_activation_hook( __FILE__, function (): void {
    if ( ! get_option( STB_OPTIONS_KEY ) ) {
        add_option( STB_OPTIONS_KEY, [
            'color'     => '#ffffff',
            'bg_color'  => '#333333',
            'position'  => 'right',
            'threshold' => 300,
            'label'     => '↑',
        ] );
    }
} );
