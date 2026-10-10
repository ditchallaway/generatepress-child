<?php
/**
 * Theme hooks — Brokertricks
 *
 * @package GeneratePressChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Remove header and footer on SureCart product pages (/products/...)
 */
add_action( 'wp', function () {
    if ( ! is_singular( 'sc_product' ) ) {
        return;
    }

    // Header (includes the floated nav), top bar, and stand-alone nav positions.
    remove_action( 'generate_header', 'generate_construct_header' );
    remove_action( 'generate_before_header', 'generate_top_bar', 5 );
    remove_action( 'generate_before_header', 'generate_add_navigation_before_header', 5 );
    remove_action( 'generate_after_header', 'generate_add_navigation_after_header', 5 );

    // Footer widgets + copyright bar.
    remove_action( 'generate_footer', 'generate_construct_footer_widgets', 5 );
    remove_action( 'generate_footer', 'generate_construct_footer' );
} );

/**
 * 2. Layout & Sidebar overrides for Custom Templates
 *
 * Forces full width (no sidebars) and removes default GeneratePress padding
 * when using our custom clean canvas or full width templates.
 */
add_filter( 'generate_sidebar_layout', function ( $layout ) {
    if ( is_page_template( array( 'template-clean-canvas.php', 'template-full-width.php' ) ) ) {
        return 'no-sidebar';
    }
    return $layout;
} );

/**
 * Clean Canvas template: option to remove default GP header/footer if requested or blank canvas
 */
add_action( 'wp', function () {
    if ( is_page_template( 'template-clean-canvas.php' ) ) {
        // Remove standard sidebar widgets just in case
        remove_action( 'generate_sidebars', 'generate_construct_sidebars' );
    }
} );
