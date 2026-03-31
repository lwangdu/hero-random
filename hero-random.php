<?php
/**
 * Plugin Name:     Random Hero Images
 * Description:     Displays a random image from an ACF gallery as a responsive and accessible hero block.
 * Version:         1.2.5
 * Author:          Lobsang Wangdu
 * Text Domain:     hero-random
 * Requires at least: 6.3
 * Requires PHP: 7.4
 * Requires Plugins: advanced-custom-fields-pro
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'admin_notices', function () {
    if ( function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( $screen && ! in_array( $screen->base, [ 'plugins', 'post', 'page', 'site-editor' ], true ) ) {
        return;
    }

    echo '<div class="notice notice-warning"><p>';
    echo esc_html__( 'Random Hero Images requires Advanced Custom Fields Pro to be installed and active.', 'hero-random' );
    echo '</p></div>';
} );

add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_register_block_type' ) ) { return; }

    $base_url  = plugin_dir_url( __FILE__ );
    $base_path = plugin_dir_path( __FILE__ );

    $front_css_path = $base_path . 'inc/hero-random.css';
    $front_css      = $base_url . 'inc/hero-random.css';
    $front_ver      = file_exists( $front_css_path ) ? (string) filemtime( $front_css_path ) : false;
    $front_css      = $front_ver ? add_query_arg( 'ver', $front_ver, $front_css ) : $front_css;

    acf_register_block_type( [
        'name'              => 'hero-random',
        'title'             => __( 'Random Hero Images', 'hero-random' ),
        'description'       => __( 'Random hero image with editable title & button slots', 'hero-random' ),
        'render_template'   => $base_path . 'inc/hero-random-block.php',
        'category'          => 'media',
        'icon'              => 'format-image',
        'enqueue_style'     => $front_css,
        'supports'          => [
            'align'           => [ 'wide', 'full' ],
            'anchor'          => true,
            'customClassName' => true,
            'jsx'             => true,
        ],
        'mode'              => 'preview',
        'keywords'          => [ 'hero', 'random', 'acf', 'image', 'banner' ],
    ] );
} );
