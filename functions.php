<?php  

function enqueue_jquery() {
        // Enqueue jQuery (WordPress's built-in version)
        wp_enqueue_script('jquery');
    }

 add_action('wp_enqueue_scripts', 'enqueue_jquery');



/**
 * Require custom fulfillment dashboard script
 */
require_once get_stylesheet_directory() . '/inc/fulfillment-dashboard.php';