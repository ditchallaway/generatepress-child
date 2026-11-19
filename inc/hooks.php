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

/**
 * 3. Completely Disable Comments sitewide + XML-RPC
 * (Migrated from Advanced Scripts: "Completely Disable Comments")
 */
add_action( 'admin_init', function () {
    global $pagenow;

    if ( $pagenow === 'edit-comments.php' ) {
        wp_safe_redirect( admin_url() );
        exit;
    }

    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

    foreach ( get_post_types() as $post_type ) {
        if ( post_type_supports( $post_type, 'comments' ) ) {
            remove_post_type_support( $post_type, 'comments' );
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
} );

add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );
add_filter( 'comments_array', '__return_empty_array', 10, 2 );

add_action( 'admin_menu', function () {
    remove_menu_page( 'edit-comments.php' );
} );

add_action( 'init', function () {
    if ( is_admin_bar_showing() ) {
        remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
    }
} );

add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * 4. Output window.pathId on Post ID 12
 * (Migrated from Advanced Scripts: "path id")
 */
add_action( 'wp_footer', function () {
    if ( ! is_singular() || get_the_ID() !== 12 ) {
        return;
    }

    echo '<script>';
    echo 'window.pathId = ' . (int) get_current_user_id() . ';';
    echo '</script>';
}, 10 );
