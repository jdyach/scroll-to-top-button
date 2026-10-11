<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class STB_Settings {

    public static function init(): void {
        add_action( 'admin_menu',  [ self::class, 'add_page' ] );
        add_action( 'admin_init',  [ self::class, 'register_settings' ] );
    }

    public static function add_page(): void {
        add_options_page(
            __( 'Scroll to Top Button', 'scroll-to-top-button' ),
            __( 'Scroll to Top', 'scroll-to-top-button' ),
            'manage_options',
            'scroll-to-top-button',
            [ self::class, 'render_page' ]
        );
    }

    public static function register_settings(): void {
        register_setting(
            'stb_settings_group',
            STB_OPTIONS_KEY,
            [ 'sanitize_callback' => [ self::class, 'sanitize' ] ]
        );

        add_settings_section(
            'stb_appearance',
            __( 'Appearance', 'scroll-to-top-button' ),
            '__return_null',
            'stb_settings_group'
        );

        $fields = [
            'color'     => __( 'Icon Color', 'scroll-to-top-button' ),
            'bg_color'  => __( 'Background Color', 'scroll-to-top-button' ),
            'position'  => __( 'Screen Position', 'scroll-to-top-button' ),
            'threshold' => __( 'Scroll Threshold (px)', 'scroll-to-top-button' ),
            'label'     => __( 'Button Label / Icon', 'scroll-to-top-button' ),
        ];

        foreach ( $fields as $id => $label ) {
            add_settings_field(
                'stb_' . $id,
                $label,
                [ self::class, 'render_field' ],
                'stb_settings_group',
                'stb_appearance',
                [ 'field' => $id ]
            );
        }
    }

    public static function render_field( array $args ): void {
        $options  = wp_parse_args( get_option( STB_OPTIONS_KEY, [] ), self::defaults() );
        $field    = $args['field'];
        $value    = $options[ $field ] ?? '';

        switch ( $field ) {
            case 'color':
            case 'bg_color':
                printf(
                    '<input type="color" name="%1$s[%2$s]" value="%3$s">',
                    esc_attr( STB_OPTIONS_KEY ),
                    esc_attr( $field ),
                    esc_attr( $value )
                );
                break;

            case 'position':
                $choices = [
                    'left'  => __( 'Bottom Left', 'scroll-to-top-button' ),
                    'right' => __( 'Bottom Right', 'scroll-to-top-button' ),
                ];
                echo '<select name="' . esc_attr( STB_OPTIONS_KEY ) . '[position]">';
                foreach ( $choices as $val => $label ) {
                    printf(
                        '<option value="%s"%s>%s</option>',
                        esc_attr( $val ),
                        selected( $value, $val, false ),
                        esc_html( $label )
                    );
                }
                echo '</select>';
                break;

            case 'threshold':
                printf(
                    '<input type="number" name="%1$s[threshold]" value="%2$s" min="0" max="9999" step="50" class="small-text"> px',
                    esc_attr( STB_OPTIONS_KEY ),
                    esc_attr( $value )
                );
                break;

            case 'label':
                printf(
                    '<input type="text" name="%1$s[label]" value="%2$s" class="regular-text">
                    <p class="description">%3$s</p>',
                    esc_attr( STB_OPTIONS_KEY ),
                    esc_attr( $value ),
                    esc_html__( 'Any text or HTML entity — e.g. ↑ or Top', 'scroll-to-top-button' )
                );
                break;
        }
    }

    public static function sanitize( mixed $input ): array {
        $defaults = self::defaults();
        return [
            'color'     => isset( $input['color'] )    ? sanitize_hex_color( $input['color'] )    ?? $defaults['color']    : $defaults['color'],
            'bg_color'  => isset( $input['bg_color'] ) ? sanitize_hex_color( $input['bg_color'] ) ?? $defaults['bg_color'] : $defaults['bg_color'],
            'position'  => in_array( $input['position'] ?? '', [ 'left', 'right' ], true ) ? $input['position'] : $defaults['position'],
            'threshold' => isset( $input['threshold'] ) ? absint( $input['threshold'] ) : $defaults['threshold'],
            'label'     => isset( $input['label'] )    ? wp_kses( $input['label'], [] )            : $defaults['label'],
        ];
    }

    public static function render_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) { return; }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'stb_settings_group' );
                do_settings_sections( 'stb_settings_group' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function defaults(): array {
        return [
            'color'     => '#ffffff',
            'bg_color'  => '#333333',
            'position'  => 'right',
            'threshold' => 300,
            'label'     => '↑',
        ];
    }
}
