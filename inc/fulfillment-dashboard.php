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
} );

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
        if ( ( $order->status ?? '' ) === 'draft' ) continue;

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
                foreach ( $notes as $note ) {
                    if ( is_array( $note ) && ! empty( $note['metadata'] ) && is_array( $note['metadata'] ) && isset( $note['metadata']['fulfilled_at'] ) ) {
                        $download_note = $note['metadata'];
                        break;
                    }
                }
            }
        }

        $parcel = $checkout_parcel_map[ $order->id ] ?? '';
        if ( empty( $parcel ) ) $parcel = btx_try_get_parcel_from_order( $order );
        if ( empty( $parcel ) && is_array( $download_note ) && ! empty( $download_note['parcel_number'] ) ) {
            $parcel = $download_note['parcel_number'];
        }
        if ( empty( $parcel ) && ( $order->fulfillment_status ?? '' ) === 'unfulfilled' && ! empty( $order->checkout ) && is_string( $order->checkout ) ) {
            try {
                $chk = \SureCart\Models\Checkout::find( $order->checkout );
                if ( $chk && ! is_wp_error( $chk ) && is_object( $chk ) ) {
                    $parcel = btx_extract_parcel_from_metadata( $chk );
                }
            } catch ( \Throwable $e ) {}
        }

        $result_orders[] = [
            'id'                 => $order->id,
            'order_number'       => $order->number ?? '',
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

    var hideQueued = false;
    function queueHide() {
        if (hideQueued) return;
        hideQueued = true;
        requestAnimationFrame(function () {
            hideQueued = false;
            hideDownloadsTab(document);
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

            const images  = data.downloads.filter(d => isImageKey(d.key));
            const kml     = data.downloads.find(d => d.key === 'kml_url');
            const hasZip  = images.length > 1;

            detailEl.querySelector('#btx-detail-title').textContent    = data.identifier;
            detailEl.querySelector('#btx-detail-subtitle').textContent = 'Fulfilled ' + data.fulfilledDate;

            const body = detailEl.querySelector('#btx-detail-body');
            body.innerHTML = '';

            // Image cards (wrapped in a grid; layout varies by image count)
            const grid = document.createElement('div');
            grid.className = `btx-img-grid btx-img-grid--count-${images.length}`;
            if (images.length) body.appendChild(grid);

            for (const img of images) {
                const card = document.createElement('div');
                card.className = 'btx-img-card';
                card.innerHTML = `
                    <div class="btx-img-card__image-wrap" id="wrap-${img.key}">
                        <img src="${img.url}" alt="${img.label}" loading="lazy">
                    </div>
                    <div class="btx-img-card__footer">
                        <span class="btx-img-card__label">${img.label}</span>
                        <a href="${img.url}" class="btx-img-card__btn" download>
                            &#8595; Download
                        </a>
                    </div>
                `;
                const wrap  = card.querySelector('.btx-img-card__image-wrap');
                const imgEl = card.querySelector('img');
                imgEl.addEventListener('load',  () => wrap.classList.add('btx-loaded'));
                imgEl.addEventListener('error', () => {
                    wrap.classList.add('btx-loaded');
                    wrap.style.minHeight = '0';
                    wrap.innerHTML = '';   // hide broken image wrapper
                });
                grid.appendChild(card);
            }

            // KML row (below images) — uses the same Download button as image cards
            if (kml) {
                const kmlRow = document.createElement('div');
                kmlRow.className = 'btx-kml-row';
                kmlRow.innerHTML = `
                    <span class="btx-kml-row__icon">📍</span>
                    <span class="btx-kml-row__label">Boundary Coordinates (KML)</span>
                    <a href="${kml.url}" class="btx-img-card__btn" download>
                        &#8595; Download
                    </a>
                `;
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

            // Show detail, hide list
            if (listEl) listEl.style.display = 'none';
            if (noteEl) noteEl.style.display = 'none';
            detailEl.classList.add('btx-detail--open');
            window.scrollTo({ top: container.offsetTop - 24, behavior: 'smooth' });
        }

        function closeDetail() {
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
                        const blob = await fetch(img.url, { mode: 'cors' }).then(r => {
                            if (!r.ok) throw new Error('fetch failed');
                            return r.blob();
                        });
                        // Guess extension from Content-Type or default to .jpg
                        const ext = blob.type === 'image/png' ? 'png' : 'jpg';
                        zip.file(`${i}-${img.label.replace(/\s+/g, '_')}.${ext}`, blob);
                        i++;
                    } catch (fetchErr) {
                        console.warn('ZIP: could not fetch', img.url, fetchErr);
                    }
                }
                if (Object.keys(zip.files).length === 0) {
                    throw new Error('No images could be fetched. This may be a CORS restriction.');
                }
                const content = await zip.generateAsync({ type: 'blob' });
                // Trigger download via temporary anchor
                const a = document.createElement('a');
                a.href = URL.createObjectURL(content);
                a.download = identifier.replace(/\s+/g, '_') + '.zip';
                a.click();
                URL.revokeObjectURL(a.href);
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
                    container.innerHTML = '<p>Please log in to view your files.</p>';
                    return false;
                }
                if (res.status === 404) {
                    if (!lastRenderKey) {
                        container.innerHTML = `
                            <h2 class="btx-files-heading">My Files</h2>
                            <p class="btx-processing-note">Files are being generated. We will also email you the download links when ready.</p>
                            <div class="btx-files-card">
                                <div class="btx-files-row btx-files-row--processing">
                                    <div class="btx-files-row__id">New Order</div>
                                    <div class="btx-files-row__date"><span class="btx-spinner"></span>Generating…</div>
                                    <div class="btx-files-row__count">ETA: 1–3 min</div>
                                    <div class="btx-files-row__status">
                                        <span class="btx-badge btx-badge--processing">Processing</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    return true; // not ready yet — keep polling
                }
                if (!res.ok) {
                    let msg = `Error ${res.status}`;
                    try { const e = await res.json(); if (e.message) msg += ': ' + e.message; } catch(_){}
                    if (!lastRenderKey) {
                        container.innerHTML = `<p>${msg}</p>`;
                    }
                    return pollCount < MAX_POLLS;
                }

                const data   = await res.json();
                const orders = data.orders || [];
                if (orders.length === 0) {
                    container.innerHTML = '<p>You have no orders yet.</p>';
                    return pollCount < 6;
                }

                // Sort: processing (still generating) orders always on top,
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
                    return !(o.metadata && o.metadata.fulfilled_at) && o.fulfillment_status === 'unfulfilled';
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
                    o.metadata && o.metadata.fulfilled_at,
                    o.metadata && o.metadata.pack,
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
                    noteEl = document.createElement('p');
                    noteEl.className   = 'btx-processing-note';
                    noteEl.textContent = 'Files are being generated. We will also email you the download links when ready.';
                    container.appendChild(noteEl);
                }

                listEl = document.createElement('div');
                listEl.className = 'btx-files-card';
                container.appendChild(listEl);

                let visibleCount = 0;

                for (const order of orders) {
                    const meta         = order.metadata;
                    const isFulfilled  = meta && meta.fulfilled_at;
                    const isProcessing = order.fulfillment_status === 'unfulfilled';
                    if (!isFulfilled && !isProcessing) continue;

                    visibleCount++;
                    const row = document.createElement('div');
                    row.className = 'btx-files-row';

                    const identifier = order.parcel
                        ? `Parcel ${order.parcel}`
                        : `Order #${order.order_number}`;

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

                        const dataIndex = orderDataStore.length;
                        orderDataStore.push({ id: order.id, identifier, meta, fulfilledDate, downloads });

                        row.addEventListener('click', function () {
                            pushOrderUrl(order.id);
                            openDetail(dataIndex);
                        });

                    } else {
                        row.classList.add('btx-files-row--processing');
                        row.innerHTML = `
                            <div class="btx-files-row__id">${identifier}</div>
                            <div class="btx-files-row__date"><span class="btx-spinner"></span>Generating…</div>
                            <div class="btx-files-row__count">ETA: 1–3 min</div>
                            <div class="btx-files-row__status">
                                <span class="btx-badge btx-badge--processing">Processing</span>
                            </div>
                        `;
                    }
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
                    container.innerHTML = `<p>Error loading your files: ${err.message}</p>`;
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
