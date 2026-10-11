<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class STB_Output {

    public static function init(): void {
        add_action( 'wp_enqueue_scripts', [ self::class, 'enqueue' ] );
        add_action( 'wp_footer',          [ self::class, 'render_button' ] );
    }

    public static function enqueue(): void {
        $options = wp_parse_args( get_option( STB_OPTIONS_KEY, [] ), STB_Settings::defaults() );

        wp_enqueue_style(
            'stb-front',
            STB_PLUGIN_URL . 'assets/css/front.css',
            [],
            STB_VERSION
        );

        // Pass settings to JS as inline variables — no build step needed.
        wp_enqueue_script(
            'stb-front',
            STB_PLUGIN_URL . 'assets/js/front.js',
            [],
            STB_VERSION,
            true
        );

        wp_localize_script( 'stb-front', 'stbConfig', [
            'threshold' => absint( $options['threshold'] ),
        ] );

        // Inject CSS custom properties so color/position are driven by PHP settings.
        $side = $options['position'] === 'left' ? 'left: 1.5rem; right: auto;' : 'right: 1.5rem; left: auto;';
        $inline = sprintf(
            ':root { --stb-color: %s; --stb-bg: %s; } #stb-btn { %s }',
            esc_attr( $options['color'] ),
            esc_attr( $options['bg_color'] ),
            $side
        );
        wp_add_inline_style( 'stb-front', $inline );
    }

    public static function render_button(): void {
        $options = wp_parse_args( get_option( STB_OPTIONS_KEY, [] ), STB_Settings::defaults() );
        // wp_kses was applied on save; output is already safe, but run through esc_html for plain-text labels.
        $label = $options['label'];
        echo '<button id="stb-btn" aria-label="' . esc_attr__( 'Scroll to top', 'scroll-to-top-button' ) . '" hidden>'
            . wp_kses( $label, [] )
            . '</button>';
    }
}
