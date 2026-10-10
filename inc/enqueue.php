<?php
/**
 * Script and Style Enqueues — Brokertricks
 *
 * @package GeneratePressChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue child theme styles with automatic filemtime cache-busting
 */
function brokertricks_enqueue_assets() {
    $theme_dir = get_stylesheet_directory();
    $theme_uri = get_stylesheet_directory_uri();

    // 1. Enqueue jQuery (WordPress built-in)
    wp_enqueue_script( 'jquery' );

    // 2. Modern Design Tokens / Variables (sitewide)
    if ( file_exists( $theme_dir . '/css/variables.css' ) ) {
        wp_enqueue_style(
            'brokertricks-variables',
            $theme_uri . '/css/variables.css',
            array(),
            filemtime( $theme_dir . '/css/variables.css' )
        );
    }

    // 3. Modern Base Responsive Styles (sitewide)
    if ( file_exists( $theme_dir . '/css/modern-base.css' ) ) {
        wp_enqueue_style(
            'brokertricks-modern-base',
            $theme_uri . '/css/modern-base.css',
            array( 'brokertricks-variables' ),
            filemtime( $theme_dir . '/css/modern-base.css' )
        );
    }

    // 4. Custom tweaks / SureCart overrides
    if ( file_exists( $theme_dir . '/css/custom.css' ) ) {
        wp_enqueue_style(
            'brokertricks-custom',
            $theme_uri . '/css/custom.css',
            array( 'brokertricks-modern-base' ),
            filemtime( $theme_dir . '/css/custom.css' )
        );
    }

    // 5. Fulfillment Dashboard & Portal Styles (only on dashboard pages or full-width app template)
    if ( is_page( array( 'dash', 'dashboard' ) ) || is_page_template( 'template-full-width.php' ) ) {
        if ( file_exists( $theme_dir . '/css/fulfillment-dashboard.css' ) ) {
            wp_enqueue_style(
                'brokertricks-fulfillment-dashboard',
                $theme_uri . '/css/fulfillment-dashboard.css',
                array( 'brokertricks-modern-base' ),
                filemtime( $theme_dir . '/css/fulfillment-dashboard.css' )
            );
        }
    }

    // 6. Custom JS if present
    if ( file_exists( $theme_dir . '/js/custom.js' ) && filesize( $theme_dir . '/js/custom.js' ) > 0 ) {
        wp_enqueue_script(
            'brokertricks-custom-js',
            $theme_uri . '/js/custom.js',
            array( 'jquery' ),
            filemtime( $theme_dir . '/js/custom.js' ),
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'brokertricks_enqueue_assets' );
