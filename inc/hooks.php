<?php
/**
 * Theme hooks — Brokertricks
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * No header or footer on SureCart product pages (/products/...).
 *
 * SureCart renders products through GeneratePress's get_header()/get_footer(),
 * so we unhook GeneratePress's header + footer pieces on sc_product singles.
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
