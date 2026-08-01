<?php  

function enqueue_jquery() {
        // Enqueue jQuery (WordPress's built-in version)
        wp_enqueue_script('jquery');
    }

 add_action('wp_enqueue_scripts', 'enqueue_jquery');

/**
 * Enqueue child theme custom styles
 */
function brokertricks_enqueue_custom_styles() {
    wp_enqueue_style(
        'brokertricks-custom',
        get_stylesheet_directory_uri() . '/css/custom.css',
        array(),
        filemtime( get_stylesheet_directory() . '/css/custom.css' )
    );
}
add_action( 'wp_enqueue_scripts', 'brokertricks_enqueue_custom_styles' );

/**
 * Require custom fulfillment dashboard script
 */
require_once get_stylesheet_directory() . '/inc/fulfillment-dashboard.php';