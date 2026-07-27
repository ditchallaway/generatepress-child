<?php  

function enqueue_jquery() {
        // Enqueue jQuery (WordPress's built-in version)
        wp_enqueue_script('jquery');
    }

 add_action('wp_enqueue_scripts', 'enqueue_jquery');



/**
 * Require custom fulfillment dashboard shortcode
 */
require_once get_stylesheet_directory() . '/inc/shortcode-fulfillment-dashboard.php';