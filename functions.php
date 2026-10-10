<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * @package GeneratePressChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue scripts and styles (modular asset loader)
 */
require_once get_stylesheet_directory() . '/inc/enqueue.php';

/**
 * Custom fulfillment dashboard logic & endpoints
 */
require_once get_stylesheet_directory() . '/inc/fulfillment-dashboard.php';

/**
 * Theme hooks, filters, and template layout overrides
 */
require_once get_stylesheet_directory() . '/inc/hooks.php';