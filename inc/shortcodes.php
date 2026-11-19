<?php
/**
 * Custom Shortcodes — Brokertricks
 * (Migrated from Advanced Scripts)
 *
 * @package GeneratePressChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. [back] — Go Back button shortcode
 * (Migrated from Advanced Scripts: "back button")
 */
add_shortcode( 'back', function () {
    ob_start();
    ?>
    <style>
    button.back{
        font-size:1rem;
        padding:.5rem;
        z-index:10;
        position:absolute;
        top:90px;
        left:35px;
    }
    button.back:hover{
        color: #000000;
        background-color: #6ba2fb;
    }</style>
    <button onclick="history.back()" class="back has-white-background-color has-background wp-element-button" style="border-width:4px;border-radius:10px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--10);font-style:normal;font-weight:700">Go Back</button>
    <?php
    return ob_get_clean();
} );

/**
 * 2. [copyright] — Dynamic year copyright line
 * (Migrated from Advanced Scripts: "Year PHP")
 */
add_shortcode( 'copyright', function () {
    return '<p style="text-align:center">Made in USA</p><p style="text-align:center">brokertricks.com &copy; ' . date( 'Y' ) . ' All rights reserved</p>';
} );

/**
 * 3. [parcel] — Output sanitized ?parcel= query parameter
 * (Migrated from Advanced Scripts: "parcel")
 */
add_shortcode( 'parcel', function () {
    if ( isset( $_GET['parcel'] ) ) {
        return sanitize_text_field( wp_unslash( $_GET['parcel'] ) );
    }
    return 'Unavailable';
} );

/**
 * 4. [smartcode] / Fluent Forms GeoIP smartcode
 * (Migrated from Advanced Scripts: "GeoIP")
 */
function brokertricks_register_geoip_smartcode() {
    static $registered = false;
    if ( $registered ) {
        return;
    }
    $registered = true;

    add_filter( 'fluentform_editor_shortcodes', function ( $smartCodes ) {
        $smartCodes[0]['shortcodes']['{ffc_geo_country}'] = 'GEO Country';
        return $smartCodes;
    } );

    add_filter( 'fluentform_editor_shortcode_callback_ffc_geo_country', function ( $value, $form ) {
        $response = file_get_contents( 'https://geokeo.com/geocode/v1/search.php?q=$value&api=35840381365b7845f9684edf4587af08' );
        return json_decode( $response );
    }, 10, 2 );
}
brokertricks_register_geoip_smartcode();

add_shortcode( 'smartcode', function () {
    brokertricks_register_geoip_smartcode();
    return '';
} );
