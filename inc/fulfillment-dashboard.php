<?php
/**
 * Fulfillment Dashboard — Brokertricks
 *
 * Renders an image-first delivery dashboard on any page with
 * id="btx-fulfillment-dashboard" in the markup.
 *
 * URL routing:
 *   /dash/                  → order list
 *   /dash/?order=ord_abc    → directly opens that order's detail panel
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_footer', 'btx_render_fulfillment_dashboard_script' );

// ── REST endpoint ─────────────────────────────────────────────────────────────
add_action( 'rest_api_init', function () {
    register_rest_route( 'btx/v1', '/fulfillment', [
        'methods'             => 'GET',
        'callback'            => 'btx_get_fulfillment_files',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( 'btx/v1', '/download', [
        'methods'             => 'GET',
        'callback'            => 'btx_proxy_download_file',
        'permission_callback' => '__return_true',
    ] );
} );

// ── Same-origin download proxy (enforces Content-Disposition: attachment) ─────
function btx_proxy_download_file( $request ) {
    $raw_url  = trim( (string) $request->get_param( 'url' ) );
    $filename = trim( (string) $request->get_param( 'filename' ) );

    if ( empty( $raw_url ) ) {
        return new WP_Error( 'missing_url', 'Missing download URL.', [ 'status' => 400 ] );
    }

    $parsed = wp_parse_url( $raw_url );
    $scheme = isset( $parsed['scheme'] ) ? strtolower( $parsed['scheme'] ) : '';
    $host   = isset( $parsed['host'] ) ? strtolower( $parsed['host'] ) : '';

    if ( ! in_array( $scheme, [ 'https', 'http' ], true ) || empty( $host ) || $host === 'localhost' ) {
        return new WP_Error( 'invalid_url', 'Invalid download URL.', [ 'status' => 400 ] );
    }

    // Resolve host IP and block loopback / private / reserved ranges (SSRF protection)
    $resolved_ip = gethostbyname( $host );
    if (
        empty( $resolved_ip ) ||
        ! filter_var( $resolved_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE )
    ) {
        return new WP_Error( 'forbidden_host', 'Download host is not permitted.', [ 'status' => 403 ] );
    }

    $response = wp_remote_get( $raw_url, [
        'timeout'     => 30,
        'redirection' => 5,
        'sslverify'   => true,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'download_failed', 'Could not retrieve file.', [ 'status' => 502 ] );
    }

    $status_code = wp_remote_retrieve_response_code( $response );
    if ( $status_code < 200 || $status_code >= 300 ) {
        return new WP_Error( 'upstream_error', 'Upstream file returned HTTP ' . intval( $status_code ), [ 'status' => 502 ] );
    }

    $body         = wp_remote_retrieve_body( $response );
    $content_type = (string) wp_remote_retrieve_header( $response, 'content-type' );
    $mime         = strtolower( trim( explode( ';', $content_type )[0] ) );

    $ext_map = [
        'image/png'                            => 'png',
        'image/jpeg'                           => 'jpg',
        'image/jpg'                            => 'jpg',
        'image/webp'                           => 'webp',
        'application/vnd.google-earth.kml+xml' => 'kml',
        'application/xml'                      => 'kml',
        'text/xml'                             => 'kml',
    ];

    $is_kml = (bool) preg_match( '/\.kml$/i', $filename ) || in_array( $mime, [ 'application/vnd.google-earth.kml+xml', 'application/xml', 'text/xml' ], true );
    if ( $is_kml ) {
        $ext  = 'kml';
        $mime = 'application/vnd.google-earth.kml+xml';
    } elseif ( isset( $ext_map[ $mime ] ) ) {
        $ext = $ext_map[ $mime ];
    } else {
        $ext  = 'jpg';
        $mime = $mime ?: 'application/octet-stream';
    }

    if ( empty( $filename ) ) {
        $filename = 'parcel-file.' . $ext;
    } else {
        // Strip existing image/kml extension and append the detected one
        $filename = preg_replace( '/\.(jpe?g|png|webp|kml)$/i', '', $filename ) . '.' . $ext;
    }

    $safe_name = sanitize_file_name( $filename );
    if ( empty( $safe_name ) ) {
        $safe_name = 'parcel-file.' . $ext;
    }

    while ( ob_get_level() > 0 ) {
        ob_end_clean();
    }

    nocache_headers();
    header( 'Content-Type: ' . $mime );
    header( 'Content-Disposition: attachment; filename="' . str_replace( [ '"', "\r", "\n" ], '', $safe_name ) . '"' );
    header( 'Content-Length: ' . strlen( $body ) );
    header( 'X-Content-Type-Options: nosniff' );
    echo $body;
    exit;
}

// ── PHP helpers ───────────────────────────────────────────────────────────────

function btx_extract_parcel_from_metadata( $checkout ) {
    if ( ! isset( $checkout->metadata ) ) return '';
    $meta = $checkout->metadata;
    if ( is_object( $meta ) && isset( $meta->parcel ) ) return $meta->parcel;
    if ( is_array( $meta )  && isset( $meta['parcel'] ) ) return $meta['parcel'];
    return '';
}

function btx_try_get_parcel_from_order( $order ) {
    try {
        if ( isset( $order->checkout ) && is_object( $order->checkout ) ) {
            return btx_extract_parcel_from_metadata( $order->checkout );
        }
    } catch ( \Throwable $e ) {}
    return '';
}

function btx_get_fulfillment_files( $request ) {
    $tokens_str = $request->get_param( 'tokens' );
    $tokens     = [];
    if ( ! empty( $tokens_str ) ) {
        $tokens = array_filter( array_map( 'trim', explode( ',', $tokens_str ) ) );
    }
    $token = $request->get_param( 'token' );
    if ( ! empty( $token ) ) $tokens[] = $token;
    $tokens = array_unique( $tokens );

    if ( ! class_exists( '\\SureCart\\Models\\Checkout' ) ) {
        return new WP_Error( 'surecart_missing', 'SureCart plugin is not active.', [ 'status' => 500 ] );
    }

    $orders_by_id         = [];
    $checkout_parcel_map  = [];
    $pending_checkouts    = [];
    $customer_ids         = [];
    $customer_emails      = [];
    $matched_checkout_ids = [];

    // 1. Resolve checkout tokens from URL / localStorage
    if ( ! empty( $tokens ) ) {
        foreach ( $tokens as $t ) {
            try {
                $checkout = \SureCart\Models\Checkout::find( $t );
            } catch ( \Throwable $e ) {
                $checkout = null;
            }
            if ( ! $checkout || is_wp_error( $checkout ) || ! is_object( $checkout ) || empty( $checkout->id ) ) {
                continue;
            }

            $parcel = btx_extract_parcel_from_metadata( $checkout );
            if ( ! empty( $checkout->email ) && is_string( $checkout->email ) ) {
                $customer_emails[] = $checkout->email;
            }
            if ( isset( $checkout->customer ) ) {
                if ( is_string( $checkout->customer ) && ! empty( $checkout->customer ) ) {
                    $customer_ids[] = $checkout->customer;
                } elseif ( is_object( $checkout->customer ) ) {
                    if ( ! empty( $checkout->customer->id ) && is_string( $checkout->customer->id ) ) {
                        $customer_ids[] = $checkout->customer->id;
                    }
                    if ( ! empty( $checkout->customer->email ) && is_string( $checkout->customer->email ) ) {
                        $customer_emails[] = $checkout->customer->email;
                    }
                } elseif ( is_array( $checkout->customer ) ) {
                    if ( ! empty( $checkout->customer['id'] ) && is_string( $checkout->customer['id'] ) ) {
                        $customer_ids[] = $checkout->customer['id'];
                    }
                    if ( ! empty( $checkout->customer['email'] ) && is_string( $checkout->customer['email'] ) ) {
                        $customer_emails[] = $checkout->customer['email'];
                    }
                }
            }

            $order_id = is_object( $checkout->order ?? null ) ? ( $checkout->order->id ?? '' ) : ( $checkout->order ?? '' );
            $order    = null;
            if ( ! empty( $order_id ) && is_string( $order_id ) ) {
                try {
                    $order = \SureCart\Models\Order::find( $order_id );
                } catch ( \Throwable $e ) {
                    $order = null;
                }
            }

            if ( $order && ! is_wp_error( $order ) && is_object( $order ) && ! empty( $order->id ) && ( $order->status ?? '' ) !== 'draft' ) {
                $checkout_parcel_map[ $order->id ]     = $parcel;
                $orders_by_id[ $order->id ]            = $order;
                $matched_checkout_ids[ $checkout->id ] = true;
            } else {
                // Checkout completed on client, but its Order is still being finalized in SureCart.
                // Include a pending entry so repeat buyers immediately see "Generating…" even
                // when a previous fulfilled order already exists.
                $pending_checkouts[ $checkout->id ] = [
                    'id'                 => 'pending_' . $checkout->id,
                    'order_number'       => ! empty( $checkout->number ) ? $checkout->number : 'Pending',
                    'fulfillment_status' => 'unfulfilled',
                    'metadata'           => null,
                    'parcel'             => $parcel,
                    'created_at'         => $checkout->updated_at ?? $checkout->created_at ?? time(),
                ];
            }
        }
    }

    // 2. Also include orders for the logged-in user and any customer found on the checkouts
    $user_id = get_current_user_id();
    if ( $user_id ) {
        $current_user = wp_get_current_user();
        if ( ! empty( $current_user->user_email ) ) {
            $customer_emails[] = $current_user->user_email;
        }
        try {
            if ( class_exists( '\\SureCart\\Models\\User' ) ) {
                $sc_user = \SureCart\Models\User::current();
                if ( $sc_user && is_object( $sc_user ) && method_exists( $sc_user, 'customerIds' ) ) {
                    $ids = $sc_user->customerIds();
                    if ( is_array( $ids ) ) {
                        $customer_ids = array_merge( $customer_ids, array_values( $ids ) );
                    }
                }
            }
        } catch ( \Throwable $e ) {}
    }

    if ( empty( $tokens ) && ! $user_id ) {
        return new WP_Error( 'unauthorized', 'You must be logged in.', [ 'status' => 401 ] );
    }

    // Resolve any customer emails into SureCart customer IDs
    $customer_emails = array_unique( array_filter( $customer_emails ) );
    if ( ! empty( $customer_emails ) && class_exists( '\\SureCart\\Models\\Customer' ) ) {
        foreach ( $customer_emails as $email ) {
            try {
                $customers = \SureCart\Models\Customer::where( [ 'email' => $email ] )->get();
                if ( ! is_wp_error( $customers ) && is_array( $customers ) ) {
                    foreach ( $customers as $c ) {
                        if ( is_object( $c ) && ! empty( $c->id ) && is_string( $c->id ) ) {
                            $customer_ids[] = $c->id;
                        }
                    }
                }
            } catch ( \Throwable $e ) {}
        }
    }

    $customer_ids = array_values( array_unique( array_filter( $customer_ids ) ) );
    if ( ! empty( $customer_ids ) ) {
        try {
            $user_orders = \SureCart\Models\Order::where( [ 'customer_ids' => $customer_ids ] )->get();
            if ( ! is_wp_error( $user_orders ) && is_array( $user_orders ) ) {
                foreach ( $user_orders as $uo ) {
                    if ( ! is_object( $uo ) || empty( $uo->id ) ) continue;
                    if ( ! isset( $orders_by_id[ $uo->id ] ) ) {
                        $orders_by_id[ $uo->id ] = $uo;
                    }
                    $uo_chk_id = is_object( $uo->checkout ?? null ) ? ( $uo->checkout->id ?? '' ) : ( $uo->checkout ?? '' );
                    if ( ! empty( $uo_chk_id ) && is_string( $uo_chk_id ) ) {
                        $matched_checkout_ids[ $uo_chk_id ] = true;
                    }
                }
            }
        } catch ( \Throwable $e ) {}
    }

    foreach ( array_keys( $matched_checkout_ids ) as $chk_id ) {
        unset( $pending_checkouts[ $chk_id ] );
    }

    if ( empty( $orders_by_id ) && empty( $pending_checkouts ) ) {
        if ( ! empty( $tokens ) ) {
            return new WP_Error( 'no_order', 'Orders not found or not created yet.', [ 'status' => 404 ] );
        }
        return [ 'orders' => [] ];
    }

    $orders_to_process = array_values( $orders_by_id );
    $result_orders     = array_values( $pending_checkouts );

    $api_token = '';
    try {
        if ( class_exists( '\\SureCart\\Models\\ApiToken' ) ) {
            $api_token = \SureCart\Models\ApiToken::get();
        }
    } catch ( \Throwable $e ) {}

    foreach ( $orders_to_process as $order ) {
        if ( in_array( $order->status ?? '', [ 'draft', 'canceled', 'void' ], true ) ) continue;

        $download_note = null;
        if ( ! empty( $api_token ) ) {
            $response = wp_remote_get(
                "https://api.surecart.com/v1/notes?notable_id={$order->id}&notable_type=order",
                [ 'headers' => [
                    'Authorization' => 'Bearer ' . $api_token,
                    'Content-Type'  => 'application/json',
                ] ]
            );

            if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
                $body  = json_decode( wp_remote_retrieve_body( $response ), true );
                $notes = [];
                if ( is_array( $body ) ) {
                    $notes = isset( $body['data'] ) && is_array( $body['data'] ) ? $body['data'] : $body;
                }
                $latest_ts = -1;
                foreach ( $notes as $note ) {
                    if ( is_array( $note ) && ! empty( $note['metadata'] ) && is_array( $note['metadata'] ) && isset( $note['metadata']['fulfilled_at'] ) ) {
                        $ts = intval( $note['updated_at'] ?? $note['created_at'] ?? 0 );
                        if ( $download_note === null || $ts >= $latest_ts ) {
                            $download_note = $note['metadata'];
                            $latest_ts     = $ts;
                        }
                    }
                }
            }
        }

        $parcel = $checkout_parcel_map[ $order->id ] ?? '';
        if ( empty( $parcel ) ) $parcel = btx_try_get_parcel_from_order( $order );
        if ( empty( $parcel ) && is_array( $download_note ) && ! empty( $download_note['parcel_number'] ) ) {
            $parcel = $download_note['parcel_number'];
        }
        if ( empty( $parcel ) && empty( $download_note ) && ! empty( $order->checkout ) && is_string( $order->checkout ) ) {
            try {
                $chk = \SureCart\Models\Checkout::find( $order->checkout );
                if ( $chk && ! is_wp_error( $chk ) && is_object( $chk ) ) {
                    $parcel = btx_extract_parcel_from_metadata( $chk );
                }
            } catch ( \Throwable $e ) {}
        }

        $result_orders[] = [
            'id'                 => $order->id,
            'order_number'       => $order->number ?? $order->order_number ?? '',
            'status'             => $order->status ?? '',
            'fulfillment_status' => $order->fulfillment_status ?? 'unfulfilled',
            'metadata'           => $download_note,
            'parcel'             => $parcel,
            'created_at'         => $order->created_at ?? null,
        ];
    }

    return [ 'orders' => $result_orders ];
}

// ── Sitewide portal tweaks (Downloads tab + download link intercept) ────────────
add_action( 'wp_footer', 'btx_inject_portal_tweaks' );

function btx_inject_portal_tweaks() {
    // Only needed on /dash/ where the SureCart customer portal lives
    if ( ! is_page( [ 'dash', 'dashboard' ] ) ) return;
    ?>
<script>
(function () {
    'use strict';

    // ── Intercept download links → redirect to our fulfillment dashboard ───────
    // When a customer is viewing an order detail:
    //   current URL:  /dash/?action=show&model=order&id=ORDER_ID
    //   clicked link: /dash/?action=show&model=download&id=DOWNLOAD_ID
    // We grab the ORDER_ID from the current page URL and redirect to:
    //   /dash/?order=ORDER_ID
    //
    // event.composedPath() lets us see links inside SureCart's shadow DOM.

    document.addEventListener('click', function (e) {
        var path   = e.composedPath ? e.composedPath() : [];
        var anchor = path.find(function (el) { return el && el.tagName === 'A'; });
        if (!anchor || !anchor.href) return;

        var linkParams = new URLSearchParams(new URL(anchor.href).search);
        // Only intercept SureCart download model links
        if (linkParams.get('action') !== 'show' || linkParams.get('model') !== 'download') return;

        e.preventDefault();

        // The order ID lives in the CURRENT page URL under ?id= when model=order
        var pageParams = new URLSearchParams(window.location.search);
        var orderId    = pageParams.get('id');

        if (orderId && pageParams.get('model') === 'order') {
            window.location.href = '/dash/?order=' + encodeURIComponent(orderId);
        } else {
            // Fallback: land on dashboard root (shows all orders)
            window.location.href = '/dash/';
        }
    }, true); // capture phase — fires before SureCart's own handlers

    // ── Hide the Downloads tab in the portal nav ──────────────────────────────
    // Don't rely on #tab-N: SureCart renumbers tabs on some views
    // (e.g. ?action=index&model=order). Match by link target instead and
    // re-apply whenever SureCart re-renders the nav.
    function isDownloadsIndexLink(href) {
        if (!href) return false;
        try {
            var p = new URL(href, window.location.href).searchParams;
            return p.get('model') === 'download' && p.get('action') !== 'show';
        } catch (err) { return false; }
    }

    function hideDownloadsTab(root) {
        var nodes = root.querySelectorAll('sc-tab, .sc-tab, [role="tab"], nav a, a[href*="model=download"]');
        nodes.forEach(function (el) {
            var href = el.getAttribute('href') || (el.querySelector('a') && el.querySelector('a').getAttribute('href'));
            if (!isDownloadsIndexLink(href)) return;
            var tab = el.closest('sc-tab, .sc-tab, [role="tab"], li') || el;
            tab.classList.add('btx-hide-downloads-tab');
            tab.style.setProperty('display', 'none', 'important');
        });
        // Also look inside open shadow roots (SureCart web components)
        root.querySelectorAll('*').forEach(function (el) {
            if (el.shadowRoot) hideDownloadsTab(el.shadowRoot);
        });
    }

    // ── Inject "View Fulfillment" button at bottom of order detail view ───────
    // When viewing /dash/?action=show&model=order&id=ORDER_ID, append a CTA
    // button at the bottom of the order details linking to /dash/?order=ORDER_ID.
    function findOrderDetailContainer() {
        var ordersBlock = document.querySelector('.wp-block-surecart-customer-orders');
        if (ordersBlock) {
            var innerSpacing = ordersBlock.querySelector('sc-spacing');
            return innerSpacing || ordersBlock;
        }
        var breadcrumbs = document.querySelector('.entry-content sc-breadcrumbs, sc-breadcrumbs');
        if (breadcrumbs && breadcrumbs.parentElement) {
            return breadcrumbs.parentElement;
        }
        var orderComp = document.querySelector('sc-customer-order, sc-order');
        if (orderComp && orderComp.parentElement) {
            return orderComp.parentElement;
        }
        var cards = document.querySelectorAll('.entry-content sc-card, sc-card');
        if (cards.length > 0 && cards[cards.length - 1].parentElement) {
            return cards[cards.length - 1].parentElement;
        }
        return document.querySelector(
            '.wp-block-surecart-customer-dashboard-area, ' +
            'sc-tab-panel:not([hidden]), ' +
            '.wp-block-surecart-customer-dashboard, ' +
            '.entry-content'
        );
    }

    function injectOrderFulfillmentButton() {
        var pageParams = new URLSearchParams(window.location.search);
        var orderId    = pageParams.get('id');
        if (!orderId || pageParams.get('model') !== 'order' || pageParams.get('action') !== 'show') {
            return;
        }

        var container = findOrderDetailContainer();
        if (!container) return;

        var fulfillmentUrl = '/dash/?order=' + encodeURIComponent(orderId);
        var existing       = document.getElementById('btx-order-fulfillment-cta');
        if (existing) {
            var existingLink = existing.querySelector('a');
            if (existingLink && existingLink.getAttribute('href') !== fulfillmentUrl) {
                existingLink.setAttribute('href', fulfillmentUrl);
            }
            if (existing.parentElement !== container || container.lastElementChild !== existing) {
                container.appendChild(existing);
            }
            return;
        }

        var wrap = document.createElement('div');
        wrap.id        = 'btx-order-fulfillment-cta';
        wrap.className = 'btx-order-fulfillment-cta';

        var link = document.createElement('a');
        link.className   = 'btx-order-fulfillment-btn';
        link.href        = fulfillmentUrl;
        link.textContent = 'View Fulfillment Files →';

        wrap.appendChild(link);
        container.appendChild(wrap);
    }

    var hideQueued = false;
    function queueHide() {
        if (hideQueued) return;
        hideQueued = true;
        requestAnimationFrame(function () {
            hideQueued = false;
            hideDownloadsTab(document);
            injectOrderFulfillmentButton();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', queueHide);
    } else {
        queueHide();
    }
    new MutationObserver(queueHide).observe(document.documentElement, { childList: true, subtree: true });

}());
</script>
    <?php
}

// ── Front-end ─────────────────────────────────────────────────────────────────

function btx_render_fulfillment_dashboard_script() {
    if ( ! is_page( [ 'dash', 'dashboard' ] ) ) return;
    ?>
<!-- btx-fulfillment-dashboard: populated by the script below -->

<script>
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        const container = document.getElementById('btx-fulfillment-dashboard');
        if (!container) return;

        // ── Config ──────────────────────────────────────────────
        const POLL_INTERVAL_MS = 5000;
        const MAX_POLLS        = 60;   // 5 min
        const WP_NONCE         = "<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>";

        // ── State ────────────────────────────────────────────────
        let pollCount      = 0;
        let orderDataStore = [];   // populated after fetch
        let listEl         = null; // .btx-files-card
        let detailEl       = null; // .btx-detail
        let noteEl         = null; // .btx-processing-note
        let lastRenderKey  = '';

        // ── Checkout tokens ──────────────────────────────────────
        function getTokens() {
            const urlParams = new URLSearchParams(window.location.search);
            let list = [];
            if (urlParams.get('sc_order')) list.push(urlParams.get('sc_order'));
            try {
                const ls = JSON.parse(localStorage.getItem('scCompletedCheckouts') || '[]');
                if (Array.isArray(ls)) {
                    ls.forEach(item => {
                        if (typeof item === 'string' && item) list.push(item);
                        else if (item && typeof item === 'object' && item.id) list.push(item.id);
                    });
                }
            } catch (e) {}
            return [...new Set(list)];
        }

        // ── Key helpers ──────────────────────────────────────────
        const PACK_SLOTS = {
            two_pack : ['overhead_url', 'wide_url'],
            six_pack : ['overhead_url', 'wide_url', 'north_url', 'south_url', 'east_url', 'west_url'],
        };
        const IMAGE_KEY_ORDER = ['overhead_url', 'wide_url', 'north_url', 'south_url', 'east_url', 'west_url'];

        function isImageKey(key) {
            return key.endsWith('_url') && key !== 'kml_url' && key !== 'map_url';
        }

        function labelForKey(key) {
            const MAP = {
                overhead_url : 'Close-Up Overhead',
                wide_url     : 'Wide-Angle Overhead',
                north_url    : 'North View',
                south_url    : 'South View',
                east_url     : 'East View',
                west_url     : 'West View',
                kml_url      : 'Boundary Coordinates (KML)',
                map_url      : 'Static Context Map',
            };
            if (MAP[key]) return MAP[key];
            const m = key.match(/^shot_(\d+)_url$/);
            if (m) return `Photo ${m[1]}`;
            return key.replace(/_url$/, '').replace(/_/g, ' ')
                      .replace(/\b\w/g, c => c.toUpperCase());
        }

        function extractDownloads(meta) {
            if (!meta) return [];
            const packSlots = PACK_SLOTS[meta.pack];
            if (packSlots) {
                const items = packSlots
                    .filter(k => Boolean(meta[k]))
                    .map(k => ({
                        key   : k,
                        url   : meta[k],
                        label : labelForKey(k),
                    }));
                if (meta.kml_url) {
                    items.push({
                        key   : 'kml_url',
                        url   : meta.kml_url,
                        label : labelForKey('kml_url'),
                    });
                }
                return items;
            }

            // Fallback for older orders without meta.pack
            const allKeys = Object.keys(meta).filter(k => k.endsWith('_url') && Boolean(meta[k]));
            const numbered = allKeys
                .filter(k => /^shot_\d+_url$/.test(k))
                .sort((a, b) => parseInt(a.match(/\d+/)[0]) - parseInt(b.match(/\d+/)[0]));
            const tail = ['map_url', 'kml_url'];
            const ordered = [
                ...IMAGE_KEY_ORDER.filter(k => allKeys.includes(k)),
                ...numbered,
                ...tail.filter(k => allKeys.includes(k)),
            ];
            const handled  = new Set(ordered);
            const leftover = allKeys.filter(k => !handled.has(k));
            return [...ordered, ...leftover].map(k => ({
                key   : k,
                url   : meta[k],
                label : labelForKey(k),
            }));
        }

        // ── URL routing ──────────────────────────────────────────
        function getOrderIdFromUrl() {
            return new URLSearchParams(window.location.search).get('order');
        }

        function pushOrderUrl(orderId) {
            const p = new URLSearchParams(window.location.search);
            p.set('order', orderId);
            history.pushState({ btxOrder: orderId }, '', '?' + p.toString());
        }

        function clearOrderUrl() {
            const p = new URLSearchParams(window.location.search);
            p.delete('order');
            const qs = p.toString();
            history.pushState({ btxOrder: null }, '', qs ? '?' + qs : window.location.pathname);
        }

        // ── Download helpers ─────────────────────────────────────
        function buildDownloadFilename(identifier, label, isKml) {
            const cleanId    = (identifier || 'Parcel').replace(/[^a-zA-Z0-9_-]+/g, '_').replace(/^_+|_+$/g, '');
            const cleanLabel = (label || 'File').replace(/[^a-zA-Z0-9_-]+/g, '_').replace(/^_+|_+$/g, '');
            const ext        = isKml ? 'kml' : 'jpg';
            return `${cleanId}_${cleanLabel}.${ext}`;
        }

        function getProxyDownloadUrl(rawUrl, filename) {
            return `/wp-json/btx/v1/download?url=${encodeURIComponent(rawUrl)}&filename=${encodeURIComponent(filename)}`;
        }

        async function fetchFileBlob(rawUrl, filename) {
            const proxyUrl = getProxyDownloadUrl(rawUrl, filename);
            try {
                const res = await fetch(proxyUrl, {
                    credentials: 'same-origin',
                    headers: { 'X-WP-Nonce': WP_NONCE },
                });
                if (res.ok) return await res.blob();
            } catch (_) {}

            const directRes = await fetch(rawUrl, { mode: 'cors' });
            if (!directRes.ok) throw new Error('Download failed');
            return await directRes.blob();
        }

        function extFromBlob(blob, isKml) {
            if (isKml) return 'kml';
            const t = (blob && blob.type) ? blob.type.toLowerCase() : '';
            if (t.includes('png'))  return 'png';
            if (t.includes('webp')) return 'webp';
            if (t.includes('kml') || t.includes('xml')) return 'kml';
            return 'jpg';
        }

        async function triggerFileDownload(e, rawUrl, identifier, label, isKml, btnEl) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            if (btnEl && btnEl.dataset.downloading === '1') return;

            const baseFilename = buildDownloadFilename(identifier, label, isKml);
            const proxyUrl     = getProxyDownloadUrl(rawUrl, baseFilename);
            const origHtml     = btnEl ? btnEl.innerHTML : '';

            if (btnEl) {
                btnEl.dataset.downloading = '1';
                btnEl.textContent = 'Downloading…';
            }

            try {
                const blob = await fetchFileBlob(rawUrl, baseFilename);
                const ext  = extFromBlob(blob, isKml);
                const finalName = baseFilename.replace(/\.(jpe?g|png|webp|kml)$/i, '') + '.' + ext;

                const objUrl = URL.createObjectURL(blob);
                const a      = document.createElement('a');
                a.style.display = 'none';
                a.href          = objUrl;
                a.download      = finalName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                setTimeout(() => URL.revokeObjectURL(objUrl), 5000);

                if (btnEl) {
                    btnEl.textContent = '✓ Downloaded';
                    setTimeout(() => {
                        delete btnEl.dataset.downloading;
                        btnEl.innerHTML = origHtml;
                    }, 2000);
                }
            } catch (err) {
                // Fallback: use same-origin attachment endpoint in a hidden iframe so page never navigates away
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = proxyUrl;
                document.body.appendChild(iframe);
                setTimeout(() => {
                    if (iframe.parentNode) iframe.parentNode.removeChild(iframe);
                }, 15000);

                if (btnEl) {
                    delete btnEl.dataset.downloading;
                    btnEl.innerHTML = origHtml;
                }
            }
        }

        // ── Interactive Pan & Zoom Gallery Lightbox ──────────────
        let galleryEl = null;
        let galleryState = {
            isOpen     : false,
            images     : [],
            index      : 0,
            identifier : '',
            scale      : 1,
            tx         : 0,
            ty         : 0,
            minScale   : 1,
            maxScale   : 8,
            isDragging : false,
            dragStartX : 0,
            dragStartY : 0,
            startTx    : 0,
            startTy    : 0,
            samples    : [],
            rafId      : null,
            pinchDist  : 0,
            pinchMidX  : 0,
            pinchMidY  : 0,
            lastTapTime: 0,
            lastTapX   : 0,
            lastTapY   : 0,
            lastFocusEl: null,
        };

        function ensureGalleryLightbox() {
            if (galleryEl) return galleryEl;

            const el = document.createElement('div');
            el.className = 'btx-gallery';
            el.id        = 'btx-gallery';
            el.setAttribute('role', 'dialog');
            el.setAttribute('aria-modal', 'true');
            el.setAttribute('aria-label', 'Interactive image gallery');
            el.setAttribute('aria-hidden', 'true');
            el.tabIndex  = -1;

            el.innerHTML = `
                <div class="btx-gallery__topbar">
                    <div class="btx-gallery__meta">
                        <span class="btx-gallery__title" id="btx-gallery-title"></span>
                        <span class="btx-gallery__counter" id="btx-gallery-counter"></span>
                    </div>
                    <div class="btx-gallery__actions">
                        <a href="#" class="btx-gallery__download-btn" id="btx-gallery-download" download>
                            &#8595; Download
                        </a>
                        <button type="button" class="btx-gallery__close-btn" id="btx-gallery-close" aria-label="Close viewer" title="Close (Esc)">
                            &times;
                        </button>
                    </div>
                </div>

                <div class="btx-gallery__viewport btx-gallery__viewport--zoom-in" id="btx-gallery-viewport">
                    <img class="btx-gallery__img" id="btx-gallery-img" src="" alt="" draggable="false">
                    <button type="button" class="btx-gallery__nav btx-gallery__nav--prev" id="btx-gallery-prev" aria-label="Previous image" title="Previous image">&#8249;</button>
                    <button type="button" class="btx-gallery__nav btx-gallery__nav--next" id="btx-gallery-next" aria-label="Next image" title="Next image">&#8250;</button>
                </div>

                <div class="btx-gallery__controls" role="toolbar" aria-label="Zoom controls">
                    <button type="button" class="btx-gallery__ctrl-btn" id="btx-gallery-zoom-out" aria-label="Zoom Out" title="Zoom Out (-)">&minus;</button>
                    <button type="button" class="btx-gallery__ctrl-btn btx-gallery__ctrl-btn--reset" id="btx-gallery-reset" aria-label="Fit to View" title="Fit to View (Double-click)">Fit to View</button>
                    <button type="button" class="btx-gallery__ctrl-btn" id="btx-gallery-zoom-in" aria-label="Zoom In" title="Zoom In (+)">&#43;</button>
                </div>
            `;

            document.body.appendChild(el);
            galleryEl = el;

            const viewport  = el.querySelector('#btx-gallery-viewport');
            const imgEl     = el.querySelector('#btx-gallery-img');
            const closeBtn  = el.querySelector('#btx-gallery-close');
            const prevBtn   = el.querySelector('#btx-gallery-prev');
            const nextBtn   = el.querySelector('#btx-gallery-next');
            const zoomInBtn = el.querySelector('#btx-gallery-zoom-in');
            const zoomOutBtn= el.querySelector('#btx-gallery-zoom-out');
            const resetBtn  = el.querySelector('#btx-gallery-reset');
            const dlBtn     = el.querySelector('#btx-gallery-download');

            function stopInertia() {
                if (galleryState.rafId) {
                    cancelAnimationFrame(galleryState.rafId);
                    galleryState.rafId = null;
                }
            }

            function clampPan(tx, ty, scale) {
                if (scale <= 1.005) {
                    return { tx: 0, ty: 0 };
                }
                const viewW  = viewport.clientWidth  || window.innerWidth;
                const viewH  = viewport.clientHeight || window.innerHeight;
                const baseW  = imgEl.offsetWidth     || viewW * 0.8;
                const baseH  = imgEl.offsetHeight    || viewH * 0.8;
                const scaledW = baseW * scale;
                const scaledH = baseH * scale;

                // Keep at least a generous portion of the image inside the viewport so it can never be dragged out of sight
                const marginX = Math.min(viewW * 0.2, 120);
                const marginY = Math.min(viewH * 0.2, 120);
                const maxTx = scaledW > viewW
                    ? (scaledW - viewW) / 2 + marginX
                    : Math.min((viewW - scaledW) * 0.35, marginX);
                const maxTy = scaledH > viewH
                    ? (scaledH - viewH) / 2 + marginY
                    : Math.min((viewH - scaledH) * 0.35, marginY);

                return {
                    tx: Math.min(maxTx, Math.max(-maxTx, tx)),
                    ty: Math.min(maxTy, Math.max(-maxTy, ty)),
                };
            }

            function updateCursorAndControls() {
                const isZoomed = galleryState.scale > 1.01;
                viewport.classList.toggle('btx-gallery__viewport--zoom-in', !isZoomed && !galleryState.isDragging);
                viewport.classList.toggle('btx-gallery__viewport--grab', isZoomed && !galleryState.isDragging);
                viewport.classList.toggle('btx-gallery__viewport--grabbing', galleryState.isDragging);

                zoomOutBtn.disabled = galleryState.scale <= galleryState.minScale + 0.01;
                zoomInBtn.disabled  = galleryState.scale >= galleryState.maxScale - 0.01;

                const pct = Math.round(galleryState.scale * 100);
                resetBtn.textContent = isZoomed ? `${pct}% · Fit to View` : 'Fit to View';
                resetBtn.classList.toggle('btx-gallery__ctrl-btn--active', isZoomed);
            }

            function applyTransform(animate) {
                const clamped = clampPan(galleryState.tx, galleryState.ty, galleryState.scale);
                galleryState.tx = clamped.tx;
                galleryState.ty = clamped.ty;

                if (animate) {
                    imgEl.classList.add('btx-gallery__img--animate');
                } else {
                    imgEl.classList.remove('btx-gallery__img--animate');
                }

                // GPU-accelerated 3D transform matrix / translate3d
                imgEl.style.transform = `translate3d(${galleryState.tx.toFixed(2)}px, ${galleryState.ty.toFixed(2)}px, 0) scale3d(${galleryState.scale.toFixed(4)}, ${galleryState.scale.toFixed(4)}, 1)`;
                updateCursorAndControls();
            }

            function zoomToPoint(newScale, clientX, clientY, animate) {
                stopInertia();
                const targetScale = Math.min(galleryState.maxScale, Math.max(galleryState.minScale, newScale));
                const rect = viewport.getBoundingClientRect();
                const cx   = rect.left + rect.width / 2;
                const cy   = rect.top  + rect.height / 2;
                const px   = clientX - cx;
                const py   = clientY - cy;

                const ratio = targetScale / galleryState.scale;
                galleryState.tx    = px - (px - galleryState.tx) * ratio;
                galleryState.ty    = py - (py - galleryState.ty) * ratio;
                galleryState.scale = targetScale;

                applyTransform(animate);
            }

            function resetToFit(animate) {
                stopInertia();
                galleryState.scale = 1;
                galleryState.tx    = 0;
                galleryState.ty    = 0;
                applyTransform(animate);
            }

            // Expose helpers on element for openGallery
            el._resetToFit = resetToFit;

            // 1. Mouse wheel & trackpad pinch zoom anchored to cursor
            viewport.addEventListener('wheel', function (e) {
                if (!galleryState.isOpen) return;
                e.preventDefault();

                let delta = e.deltaY;
                if (e.deltaMode === 1) delta *= 24;
                else if (e.deltaMode === 2) delta *= 300;

                const speed  = e.ctrlKey ? 0.01 : 0.0022;
                const factor = Math.exp(-delta * speed);
                zoomToPoint(galleryState.scale * factor, e.clientX, e.clientY, false);
            }, { passive: false });

            // 2. Double-click to snap back to "fit to screen" (or zoom in at cursor if already at fit-to-screen)
            viewport.addEventListener('dblclick', function (e) {
                if (e.target.closest('button, a')) return;
                e.preventDefault();
                if (galleryState.scale > 1.01 || Math.abs(galleryState.tx) > 1 || Math.abs(galleryState.ty) > 1) {
                    resetToFit(true);
                } else {
                    zoomToPoint(2.5, e.clientX, e.clientY, true);
                }
            });

            // 3. Mouse drag to pan with momentum / inertia
            let mouseDownBg = false;
            viewport.addEventListener('mousedown', function (e) {
                if (e.button !== 0 || e.target.closest('button, a')) return;
                stopInertia();
                mouseDownBg = (e.target === viewport);

                if (galleryState.scale <= 1.01) return;
                e.preventDefault();
                galleryState.isDragging = true;
                galleryState.dragStartX = e.clientX;
                galleryState.dragStartY = e.clientY;
                galleryState.startTx    = galleryState.tx;
                galleryState.startTy    = galleryState.ty;
                galleryState.samples    = [{ x: e.clientX, y: e.clientY, t: performance.now() }];
                updateCursorAndControls();
            });

            window.addEventListener('mousemove', function (e) {
                if (!galleryState.isDragging) return;
                const dx = e.clientX - galleryState.dragStartX;
                const dy = e.clientY - galleryState.dragStartY;
                if (Math.abs(dx) > 3 || Math.abs(dy) > 3) mouseDownBg = false;

                galleryState.tx = galleryState.startTx + dx;
                galleryState.ty = galleryState.startTy + dy;
                applyTransform(false);

                const now = performance.now();
                galleryState.samples.push({ x: e.clientX, y: e.clientY, t: now });
                while (galleryState.samples.length > 2 && now - galleryState.samples[0].t > 100) {
                    galleryState.samples.shift();
                }
            });

            function startMomentumFromSamples() {
                const samples = galleryState.samples;
                if (samples.length < 2 || galleryState.scale <= 1.01) return;
                const now   = performance.now();
                const last  = samples[samples.length - 1];
                if (now - last.t > 80) return;

                const first = samples[0];
                const dt    = last.t - first.t;
                if (dt < 8) return;

                let vx = (last.x - first.x) / dt; // px per ms
                let vy = (last.y - first.y) / dt;
                if (Math.hypot(vx, vy) < 0.12) return;

                let prevTime = performance.now();
                function step(ts) {
                    const frameDt = Math.min(32, Math.max(1, ts - prevTime));
                    prevTime = ts;

                    const decay = Math.pow(0.92, frameDt / 16.67);
                    vx *= decay;
                    vy *= decay;

                    const nextTx  = galleryState.tx + vx * frameDt;
                    const nextTy  = galleryState.ty + vy * frameDt;
                    const clamped = clampPan(nextTx, nextTy, galleryState.scale);

                    if (Math.abs(clamped.tx - nextTx) > 0.5) vx = 0;
                    if (Math.abs(clamped.ty - nextTy) > 0.5) vy = 0;

                    galleryState.tx = clamped.tx;
                    galleryState.ty = clamped.ty;
                    applyTransform(false);

                    if (Math.hypot(vx, vy) >= 0.04) {
                        galleryState.rafId = requestAnimationFrame(step);
                    } else {
                        galleryState.rafId = null;
                    }
                }
                galleryState.rafId = requestAnimationFrame(step);
            }

            window.addEventListener('mouseup', function (e) {
                if (galleryState.isDragging) {
                    galleryState.isDragging = false;
                    updateCursorAndControls();
                    galleryState.samples.push({ x: e.clientX, y: e.clientY, t: performance.now() });
                    startMomentumFromSamples();
                } else if (mouseDownBg && e.target === viewport && galleryState.scale <= 1.01) {
                    closeGallery();
                }
                mouseDownBg = false;
            });

            // 4. Touch: multi-touch pinch to zoom (anchored to finger midpoint) + single-touch pan with inertia + double-tap reset
            viewport.addEventListener('touchstart', function (e) {
                if (e.target.closest('button, a')) return;
                stopInertia();

                if (e.touches.length === 2) {
                    e.preventDefault();
                    galleryState.isDragging = false;
                    const t1 = e.touches[0], t2 = e.touches[1];
                    const rect = viewport.getBoundingClientRect();
                    const cx   = rect.left + rect.width / 2;
                    const cy   = rect.top  + rect.height / 2;
                    galleryState.pinchDist = Math.hypot(t2.clientX - t1.clientX, t2.clientY - t1.clientY);
                    galleryState.pinchMidX = (t1.clientX + t2.clientX) / 2 - cx;
                    galleryState.pinchMidY = (t1.clientY + t2.clientY) / 2 - cy;
                    updateCursorAndControls();
                } else if (e.touches.length === 1) {
                    const t   = e.touches[0];
                    const now = performance.now();
                    if (
                        now - galleryState.lastTapTime < 280 &&
                        Math.hypot(t.clientX - galleryState.lastTapX, t.clientY - galleryState.lastTapY) < 28
                    ) {
                        e.preventDefault();
                        galleryState.lastTapTime = 0;
                        if (galleryState.scale > 1.01 || Math.abs(galleryState.tx) > 1 || Math.abs(galleryState.ty) > 1) {
                            resetToFit(true);
                        } else {
                            zoomToPoint(2.5, t.clientX, t.clientY, true);
                        }
                        return;
                    }
                    galleryState.lastTapTime = now;
                    galleryState.lastTapX    = t.clientX;
                    galleryState.lastTapY    = t.clientY;

                    if (galleryState.scale > 1.01) {
                        galleryState.isDragging = true;
                        galleryState.dragStartX = t.clientX;
                        galleryState.dragStartY = t.clientY;
                        galleryState.startTx    = galleryState.tx;
                        galleryState.startTy    = galleryState.ty;
                        galleryState.samples    = [{ x: t.clientX, y: t.clientY, t: now }];
                        updateCursorAndControls();
                    }
                }
            }, { passive: false });

            viewport.addEventListener('touchmove', function (e) {
                if (!galleryState.isOpen) return;

                if (e.touches.length === 2) {
                    e.preventDefault();
                    const t1   = e.touches[0], t2 = e.touches[1];
                    const rect = viewport.getBoundingClientRect();
                    const cx   = rect.left + rect.width / 2;
                    const cy   = rect.top  + rect.height / 2;
                    const dist = Math.hypot(t2.clientX - t1.clientX, t2.clientY - t1.clientY);
                    const midX = (t1.clientX + t2.clientX) / 2 - cx;
                    const midY = (t1.clientY + t2.clientY) / 2 - cy;

                    if (galleryState.pinchDist > 0) {
                        const rawRatio   = dist / galleryState.pinchDist;
                        const nextScale  = Math.min(galleryState.maxScale, Math.max(galleryState.minScale, galleryState.scale * rawRatio));
                        const scaleRatio = nextScale / galleryState.scale;

                        galleryState.tx    = midX - (galleryState.pinchMidX - galleryState.tx) * scaleRatio;
                        galleryState.ty    = midY - (galleryState.pinchMidY - galleryState.ty) * scaleRatio;
                        galleryState.scale = nextScale;
                        applyTransform(false);
                    }

                    galleryState.pinchDist = dist;
                    galleryState.pinchMidX = midX;
                    galleryState.pinchMidY = midY;
                } else if (e.touches.length === 1 && galleryState.isDragging) {
                    e.preventDefault();
                    const t   = e.touches[0];
                    const now = performance.now();
                    galleryState.tx = galleryState.startTx + (t.clientX - galleryState.dragStartX);
                    galleryState.ty = galleryState.startTy + (t.clientY - galleryState.dragStartY);
                    applyTransform(false);

                    galleryState.samples.push({ x: t.clientX, y: t.clientY, t: now });
                    while (galleryState.samples.length > 2 && now - galleryState.samples[0].t > 100) {
                        galleryState.samples.shift();
                    }
                }
            }, { passive: false });

            viewport.addEventListener('touchend', function (e) {
                if (e.touches.length === 0 && galleryState.isDragging) {
                    galleryState.isDragging = false;
                    updateCursorAndControls();
                    startMomentumFromSamples();
                } else if (e.touches.length === 1) {
                    // Transition smoothly from 2-finger pinch to 1-finger pan
                    const t = e.touches[0];
                    galleryState.pinchDist  = 0;
                    galleryState.isDragging = galleryState.scale > 1.01;
                    galleryState.dragStartX = t.clientX;
                    galleryState.dragStartY = t.clientY;
                    galleryState.startTx    = galleryState.tx;
                    galleryState.startTy    = galleryState.ty;
                    galleryState.samples    = [{ x: t.clientX, y: t.clientY, t: performance.now() }];
                    updateCursorAndControls();
                }
            });

            // 5. Floating overlay controls & navigation buttons
            zoomInBtn.addEventListener('click', function () {
                const rect = viewport.getBoundingClientRect();
                zoomToPoint(galleryState.scale * 1.4, rect.left + rect.width / 2, rect.top + rect.height / 2, true);
            });

            zoomOutBtn.addEventListener('click', function () {
                const rect = viewport.getBoundingClientRect();
                zoomToPoint(galleryState.scale / 1.4, rect.left + rect.width / 2, rect.top + rect.height / 2, true);
            });

            resetBtn.addEventListener('click', function () {
                resetToFit(true);
            });

            closeBtn.addEventListener('click', closeGallery);
            prevBtn.addEventListener('click', () => stepGallery(-1));
            nextBtn.addEventListener('click', () => stepGallery(1));

            dlBtn.addEventListener('click', function (e) {
                const currentImg = galleryState.images[galleryState.index];
                if (!currentImg) return;
                triggerFileDownload(e, currentImg.url, galleryState.identifier, currentImg.label, false, dlBtn);
            });

            // 6. Keyboard shortcuts (Esc, Arrows, +, -, 0)
            window.addEventListener('keydown', function (e) {
                if (!galleryState.isOpen) return;
                if (e.key === 'Escape') {
                    e.preventDefault();
                    closeGallery();
                } else if (e.key === 'ArrowLeft' && galleryState.images.length > 1) {
                    e.preventDefault();
                    stepGallery(-1);
                } else if (e.key === 'ArrowRight' && galleryState.images.length > 1) {
                    e.preventDefault();
                    stepGallery(1);
                } else if (e.key === '+' || e.key === '=') {
                    e.preventDefault();
                    zoomInBtn.click();
                } else if (e.key === '-' || e.key === '_') {
                    e.preventDefault();
                    zoomOutBtn.click();
                } else if (e.key === '0') {
                    e.preventDefault();
                    resetToFit(true);
                }
            });

            window.addEventListener('resize', function () {
                if (galleryState.isOpen) applyTransform(false);
            });

            return el;
        }

        function renderGallerySlide() {
            const el  = ensureGalleryLightbox();
            const img = galleryState.images[galleryState.index];
            if (!img) return;

            const imgEl      = el.querySelector('#btx-gallery-img');
            const titleEl    = el.querySelector('#btx-gallery-title');
            const counterEl  = el.querySelector('#btx-gallery-counter');
            const prevBtn    = el.querySelector('#btx-gallery-prev');
            const nextBtn    = el.querySelector('#btx-gallery-next');
            const dlBtn      = el.querySelector('#btx-gallery-download');

            el._resetToFit(false);

            imgEl.src          = img.url;
            imgEl.alt          = img.label;
            titleEl.textContent = `${galleryState.identifier} — ${img.label}`;

            const total = galleryState.images.length;
            counterEl.textContent   = total > 1 ? `${galleryState.index + 1} / ${total}` : '';
            counterEl.style.display = total > 1 ? '' : 'none';
            prevBtn.style.display   = total > 1 ? '' : 'none';
            nextBtn.style.display   = total > 1 ? '' : 'none';

            const filename = buildDownloadFilename(galleryState.identifier, img.label, false);
            dlBtn.href     = getProxyDownloadUrl(img.url, filename);
            dlBtn.setAttribute('download', filename);
        }

        function openGallery(images, index, identifier, triggerEl) {
            const el = ensureGalleryLightbox();
            galleryState.isOpen      = true;
            galleryState.images      = images;
            galleryState.index       = index;
            galleryState.identifier  = identifier;
            galleryState.lastFocusEl = triggerEl || document.activeElement;

            renderGallerySlide();
            el.classList.add('btx-gallery--open');
            el.setAttribute('aria-hidden', 'false');
            document.body.classList.add('btx-gallery-lock');
            el.focus();
        }

        function stepGallery(delta) {
            const total = galleryState.images.length;
            if (total <= 1) return;
            galleryState.index = (galleryState.index + delta + total) % total;
            renderGallerySlide();
        }

        function closeGallery() {
            if (!galleryEl || !galleryState.isOpen) return;
            galleryState.isOpen = false;
            galleryEl.classList.remove('btx-gallery--open');
            galleryEl.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('btx-gallery-lock');
            if (galleryState.lastFocusEl && typeof galleryState.lastFocusEl.focus === 'function') {
                galleryState.lastFocusEl.focus();
            }
        }

        // ── Detail panel ─────────────────────────────────────────
        function buildDetailPanel() {
            const el = document.createElement('div');
            el.className = 'btx-detail';
            el.id        = 'btx-detail';
            el.innerHTML = `
                <div class="btx-detail__header">
                    <button class="btx-detail__back" id="btx-detail-back">&#8592; Back</button>
                    <h2 class="btx-detail__title" id="btx-detail-title"></h2>
                    <span class="btx-detail__subtitle" id="btx-detail-subtitle"></span>
                </div>
                <div class="btx-detail__body" id="btx-detail-body"></div>
            `;
            el.querySelector('#btx-detail-back').addEventListener('click', function () {
                clearOrderUrl();
                closeDetail();
            });
            return el;
        }

        function openDetail(index) {
            const data = orderDataStore[index];
            if (!data) return;

            detailEl.querySelector('#btx-detail-title').textContent = data.identifier;
            const subtitleEl = detailEl.querySelector('#btx-detail-subtitle');
            const body       = detailEl.querySelector('#btx-detail-body');
            body.innerHTML   = '';

            if (!data.isFulfilled) {
                subtitleEl.innerHTML = '<span class="btx-badge btx-badge--processing">Processing</span>';
                body.innerHTML = `
                    <div class="btx-processing-banner">
                        <div class="btx-processing-banner__header">
                            <span class="btx-spinner btx-spinner--lg"></span>
                            <div>
                                <div class="btx-processing-banner__title">Generating your parcel visuals…</div>
                                <div class="btx-processing-banner__sub">Estimated time: 1–3 minutes. This page will update automatically as soon as your files are ready, and we'll also email you the download links.</div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                const images = data.downloads.filter(d => isImageKey(d.key));
                const kml    = data.downloads.find(d => d.key === 'kml_url');
                const hasZip = images.length > 1;

                subtitleEl.textContent = 'Fulfilled ' + data.fulfilledDate;

                // Image cards (wrapped in a grid; layout varies by image count)
                const grid = document.createElement('div');
                grid.className = `btx-img-grid btx-img-grid--count-${images.length}`;
                if (images.length) body.appendChild(grid);

                images.forEach((img, imgIdx) => {
                    const filename = buildDownloadFilename(data.identifier, img.label, false);
                    const proxyUrl = getProxyDownloadUrl(img.url, filename);

                    const card = document.createElement('div');
                    card.className = 'btx-img-card';
                    card.innerHTML = `
                        <div class="btx-img-card__image-wrap" id="wrap-${img.key}" role="button" tabindex="0" aria-label="View ${img.label} full screen">
                            <img src="${img.url}" alt="${img.label}" loading="lazy">
                            <span class="btx-img-card__zoom-hint" aria-hidden="true">&#128269; Click to inspect</span>
                        </div>
                        <div class="btx-img-card__footer">
                            <span class="btx-img-card__label">${img.label}</span>
                            <a href="${proxyUrl}" class="btx-img-card__btn" download="${filename}">
                                &#8595; Download
                            </a>
                        </div>
                    `;
                    const wrap  = card.querySelector('.btx-img-card__image-wrap');
                    const imgEl = card.querySelector('img');
                    const dlBtn = card.querySelector('.btx-img-card__btn');

                    wrap.addEventListener('click', () => openGallery(images, imgIdx, data.identifier, wrap));
                    wrap.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            openGallery(images, imgIdx, data.identifier, wrap);
                        }
                    });

                    dlBtn.addEventListener('click', (e) => {
                        triggerFileDownload(e, img.url, data.identifier, img.label, false, dlBtn);
                    });

                    imgEl.addEventListener('load',  () => wrap.classList.add('btx-loaded'));
                    imgEl.addEventListener('error', () => {
                        if (!imgEl.dataset.retried) {
                            imgEl.dataset.retried = '1';
                            setTimeout(() => { imgEl.src = img.url; }, 1200);
                            return;
                        }
                        wrap.classList.add('btx-loaded');
                        wrap.style.minHeight = '0';
                        wrap.innerHTML = '';   // hide broken image wrapper
                    });
                    grid.appendChild(card);
                });

                // KML row (below images) — uses the same Download button as image cards
                if (kml) {
                    const kmlFilename = buildDownloadFilename(data.identifier, 'Boundary_Coordinates', true);
                    const kmlProxyUrl = getProxyDownloadUrl(kml.url, kmlFilename);
                    const kmlRow      = document.createElement('div');
                    kmlRow.className  = 'btx-kml-row';
                    kmlRow.innerHTML  = `
                        <span class="btx-kml-row__icon">📍</span>
                        <span class="btx-kml-row__label">Boundary Coordinates (KML)</span>
                        <a href="${kmlProxyUrl}" class="btx-img-card__btn" download="${kmlFilename}">
                            &#8595; Download
                        </a>
                    `;
                    const kmlBtn = kmlRow.querySelector('.btx-img-card__btn');
                    kmlBtn.addEventListener('click', (e) => {
                        triggerFileDownload(e, kml.url, data.identifier, 'Boundary_Coordinates', true, kmlBtn);
                    });
                    body.appendChild(kmlRow);
                }

                // Download All (optional — only when >1 image)
                if (hasZip) {
                    const zipBtn = document.createElement('button');
                    zipBtn.className = 'btx-download-all';
                    zipBtn.innerHTML = '&#128230; Download All Images as ZIP';
                    zipBtn.addEventListener('click', () => downloadAllAsZip(images, data.identifier, zipBtn));
                    body.appendChild(zipBtn);
                }
            }

            // Show detail, hide list
            if (listEl) listEl.style.display = 'none';
            if (noteEl) noteEl.style.display = 'none';
            detailEl.classList.add('btx-detail--open');
            window.scrollTo({ top: container.offsetTop - 24, behavior: 'smooth' });
        }

        function closeDetail() {
            closeGallery();
            detailEl.classList.remove('btx-detail--open');
            detailEl.querySelector('#btx-detail-body').innerHTML = '';
            if (listEl) listEl.style.display = '';
            if (noteEl) noteEl.style.display = '';
        }

        // ── Browser back / forward ───────────────────────────────
        window.addEventListener('popstate', function (e) {
            const orderId = e.state?.btxOrder;
            if (orderId) {
                const idx = orderDataStore.findIndex(o => o.id === orderId);
                if (idx > -1) openDetail(idx);
            } else {
                closeDetail();
            }
        });

        // ── Download All as ZIP ──────────────────────────────────
        async function loadScript(src) {
            return new Promise((resolve, reject) => {
                if (document.querySelector(`script[src="${src}"]`)) return resolve();
                const s = document.createElement('script');
                s.src = src; s.onload = resolve; s.onerror = reject;
                document.head.appendChild(s);
            });
        }

        async function downloadAllAsZip(images, identifier, btn) {
            btn.disabled    = true;
            btn.textContent = 'Preparing ZIP…';
            try {
                await loadScript('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js');
                const zip = new JSZip();
                let i = 1;
                for (const img of images) {
                    try {
                        const baseFilename = buildDownloadFilename(identifier, img.label, false);
                        const blob = await fetchFileBlob(img.url, baseFilename);
                        const ext  = extFromBlob(blob, false);
                        zip.file(`${i}-${img.label.replace(/\s+/g, '_')}.${ext}`, blob);
                        i++;
                    } catch (fetchErr) {
                        console.warn('ZIP: could not fetch', img.url, fetchErr);
                    }
                }
                if (Object.keys(zip.files).length === 0) {
                    throw new Error('No images could be fetched.');
                }
                const content = await zip.generateAsync({ type: 'blob' });
                // Trigger download via temporary anchor
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = URL.createObjectURL(content);
                a.download = identifier.replace(/\s+/g, '_') + '.zip';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                setTimeout(() => URL.revokeObjectURL(a.href), 5000);
                btn.textContent = '✓ Downloaded';
                setTimeout(() => {
                    btn.disabled    = false;
                    btn.innerHTML   = '&#128230; Download All Images as ZIP';
                }, 3000);
            } catch (err) {
                console.error('ZIP download failed:', err);
                btn.disabled    = false;
                btn.textContent = 'ZIP failed — please use individual buttons above';
                setTimeout(() => { btn.innerHTML = '&#128230; Download All Images as ZIP'; }, 4000);
            }
        }

        // ── Render helpers ───────────────────────────────────────
        function renderStatusShell(titleText, subtitleText, rowIdText, rowDateText, rowCountText, badgeText) {
            return `
                <h2 class="btx-files-heading">My Files</h2>
                <div class="btx-processing-banner">
                    <div class="btx-processing-banner__header">
                        <span class="btx-spinner btx-spinner--lg"></span>
                        <div>
                            <div class="btx-processing-banner__title">${titleText}</div>
                            <div class="btx-processing-banner__sub">${subtitleText}</div>
                        </div>
                    </div>
                </div>
                <div class="btx-files-card">
                    <div class="btx-files-row btx-files-row--processing">
                        <div class="btx-files-row__id">${rowIdText}</div>
                        <div class="btx-files-row__date"><span class="btx-spinner"></span>${rowDateText}</div>
                        <div class="btx-files-row__count">${rowCountText}</div>
                        <div class="btx-files-row__status">
                            <span class="btx-badge btx-badge--processing">${badgeText}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Render initial status immediately so the dashboard is never blank
        container.innerHTML = renderStatusShell(
            'Checking order fulfillment status…',
            'Connecting to your account and checking for parcel files…',
            'Loading orders…',
            'Checking status…',
            'Please wait',
            'Checking'
        );

        // ── Fetch & render ───────────────────────────────────────
        async function fetchAndRenderFiles() {
            try {
                const tokens   = getTokens();
                const endpoint = tokens.length > 0
                    ? `/wp-json/btx/v1/fulfillment?tokens=${encodeURIComponent(tokens.join(','))}`
                    : '/wp-json/btx/v1/fulfillment';

                const res = await fetch(endpoint, {
                    credentials: 'same-origin',
                    headers: { 'X-WP-Nonce': WP_NONCE },
                });

                if (res.status === 401) {
                    container.innerHTML = '<h2 class="btx-files-heading">My Files</h2><p>Please log in to view your files.</p>';
                    return false;
                }
                if (res.status === 404) {
                    if (!lastRenderKey) {
                        container.innerHTML = renderStatusShell(
                            'Order received — generating your parcel visuals…',
                            'Your order is being processed. Estimated time: 1–3 minutes. This page will update automatically when your files are ready, and we will also email you the download links.',
                            'New Order',
                            'Generating files…',
                            'ETA: 1–3 min',
                            'Processing'
                        );
                    }
                    return true; // not ready yet — keep polling
                }
                if (!res.ok) {
                    if (!lastRenderKey) {
                        container.innerHTML = renderStatusShell(
                            'Checking order fulfillment status…',
                            'Your order is being finalized. This page will update automatically as soon as your files are ready.',
                            'Recent Order',
                            'Syncing status…',
                            'Retrying…',
                            'Processing'
                        );
                    }
                    return pollCount < MAX_POLLS;
                }

                const data   = await res.json();
                const orders = data.orders || [];
                if (orders.length === 0) {
                    if (tokens.length > 0 && pollCount < 12) {
                        if (!lastRenderKey) {
                            container.innerHTML = renderStatusShell(
                                'Order received — preparing your parcel visuals…',
                                'Your order is being finalized. Estimated time: 1–3 minutes. This page will update automatically when your files are ready.',
                                'New Order',
                                'Generating files…',
                                'ETA: 1–3 min',
                                'Processing'
                            );
                        }
                        return true;
                    }
                    container.innerHTML = '<h2 class="btx-files-heading">My Files</h2><p>You have no orders yet.</p>';
                    return pollCount < 6;
                }

                // Sort: unfulfilled (still generating) orders always on top,
                // then newest first — prefer fulfilled_at, fall back to created_at.
                function orderTime(o) {
                    var v = (o.metadata && o.metadata.fulfilled_at) || o.created_at || 0;
                    // SureCart created_at is a unix timestamp in seconds
                    if (typeof v === 'number' || /^\d+$/.test(String(v))) {
                        v = Number(v);
                        return v < 1e12 ? v * 1000 : v;
                    }
                    return new Date(v).getTime() || 0;
                }
                function isPending(o) {
                    return !(o.metadata && o.metadata.fulfilled_at);
                }
                orders.sort(function (a, b) {
                    var pa = isPending(a), pb = isPending(b);
                    if (pa !== pb) return pa ? -1 : 1;
                    return orderTime(b) - orderTime(a);
                });

                const isWaitingForFiles = orders.some(isPending);
                const renderKey = JSON.stringify(orders.map(o => [
                    o.id,
                    o.fulfillment_status,
                    o.parcel,
                    o.metadata,
                ]));

                if (renderKey === lastRenderKey) {
                    return isWaitingForFiles || pollCount < 6;
                }
                lastRenderKey = renderKey;

                // ── Rebuild list ─────────────────────────────────
                container.innerHTML = '';
                orderDataStore = [];
                noteEl         = null;

                const heading = document.createElement('h2');
                heading.className   = 'btx-files-heading';
                heading.textContent = 'My Files';
                container.appendChild(heading);

                // Detail panel (once, always present in DOM)
                detailEl = buildDetailPanel();
                container.appendChild(detailEl);

                if (isWaitingForFiles) {
                    noteEl = document.createElement('div');
                    noteEl.className = 'btx-processing-banner';
                    noteEl.innerHTML = `
                        <div class="btx-processing-banner__header">
                            <span class="btx-spinner btx-spinner--lg"></span>
                            <div>
                                <div class="btx-processing-banner__title">Generating your parcel visuals…</div>
                                <div class="btx-processing-banner__sub">Estimated time: 1–3 minutes. This page updates automatically as soon as your files are ready, and we'll also email you the download links.</div>
                            </div>
                        </div>
                    `;
                    container.appendChild(noteEl);
                }

                listEl = document.createElement('div');
                listEl.className = 'btx-files-card';
                container.appendChild(listEl);

                let visibleCount = 0;

                for (const order of orders) {
                    const meta        = order.metadata;
                    const isFulfilled = Boolean(meta && meta.fulfilled_at);

                    visibleCount++;
                    const row = document.createElement('div');
                    row.className = 'btx-files-row';

                    const identifier = order.parcel
                        ? `Parcel ${order.parcel}`
                        : (order.order_number ? `Order #${order.order_number}` : 'New Order');

                    const dataIndex = orderDataStore.length;

                    if (isFulfilled) {
                        const downloads = extractDownloads(meta);
                        const imgCount  = downloads.filter(d => isImageKey(d.key)).length;
                        const fulfilledDate = new Date(meta.fulfilled_at)
                            .toLocaleDateString('en-US', { month:'long', day:'numeric', year:'numeric' });

                        row.innerHTML = `
                            <div class="btx-files-row__id">${identifier}</div>
                            <div class="btx-files-row__date">${fulfilledDate}</div>
                            <div class="btx-files-row__count">${imgCount} image${imgCount !== 1 ? 's' : ''}</div>
                            <div class="btx-files-row__status">
                                <span class="btx-badge btx-badge--fulfilled">Fulfilled</span>
                            </div>
                        `;

                        orderDataStore.push({ id: order.id, identifier, isFulfilled: true, meta, fulfilledDate, downloads });
                    } else {
                        row.classList.add('btx-files-row--processing');
                        row.innerHTML = `
                            <div class="btx-files-row__id">${identifier}</div>
                            <div class="btx-files-row__date"><span class="btx-spinner"></span>Generating files…</div>
                            <div class="btx-files-row__count">ETA: 1–3 min</div>
                            <div class="btx-files-row__status">
                                <span class="btx-badge btx-badge--processing">Processing</span>
                            </div>
                        `;

                        orderDataStore.push({ id: order.id, identifier, isFulfilled: false, meta: null, fulfilledDate: '', downloads: [] });
                    }

                    row.addEventListener('click', function () {
                        pushOrderUrl(order.id);
                        openDetail(dataIndex);
                    });

                    listEl.appendChild(row);
                }

                if (visibleCount === 0) {
                    container.innerHTML += '<p>No files available for your orders.</p>';
                }

                // ── Restore URL state (deep link / page refresh) ─
                const deepLinkId = getOrderIdFromUrl();
                if (deepLinkId) {
                    const idx = orderDataStore.findIndex(o => o.id === deepLinkId);
                    if (idx > -1) openDetail(idx);
                }

                // Keep polling while any order is generating, and always poll for the
                // first ~30s (pollCount < 6) in case a repeat buyer's new order is still
                // being created by SureCart alongside their previous fulfilled order.
                return isWaitingForFiles || pollCount < 6;

            } catch (err) {
                if (!lastRenderKey) {
                    container.innerHTML = renderStatusShell(
                        'Checking order fulfillment status…',
                        'Connecting to server… This page will update automatically as soon as your files are ready.',
                        'Recent Order',
                        'Reconnecting…',
                        'Retrying…',
                        'Processing'
                    );
                }
                return pollCount < MAX_POLLS;
            }
        }

        async function runPoller() {
            const keepGoing = await fetchAndRenderFiles();
            if (keepGoing && pollCount++ < MAX_POLLS) {
                setTimeout(runPoller, POLL_INTERVAL_MS);
            }
        }

        runPoller();
    });
}());
</script>
    <?php
}
