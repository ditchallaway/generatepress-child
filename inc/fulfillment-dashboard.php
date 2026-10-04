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
    } catch ( \Exception $e ) {}
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

    $orders_to_process    = [];
    $checkout_parcel_map  = [];

    if ( ! empty( $tokens ) ) {
        foreach ( $tokens as $t ) {
            $checkout = \SureCart\Models\Checkout::find( $t );
            if ( $checkout ) {
                $parcel   = btx_extract_parcel_from_metadata( $checkout );
                $order_id = is_object( $checkout->order ) ? $checkout->order->id : $checkout->order;
                if ( ! empty( $order_id ) ) {
                    $order = \SureCart\Models\Order::find( $order_id );
                    if ( $order ) {
                        $checkout_parcel_map[ $order->id ] = $parcel;
                        $orders_to_process[] = $order;
                    }
                }
            }
        }
        if ( empty( $orders_to_process ) ) {
            return new WP_Error( 'no_order', 'Orders not found or not created yet.', [ 'status' => 404 ] );
        }
    } else {
        $user_id = get_current_user_id();
        if ( ! $user_id ) {
            return new WP_Error( 'unauthorized', 'You must be logged in.', [ 'status' => 401 ] );
        }
        $current_user      = wp_get_current_user();
        $orders            = \SureCart\Models\Order::where( 'customer.email', $current_user->user_email )
                                ->orderBy( 'created_at', 'desc' )
                                ->get();
        if ( empty( $orders ) ) return [ 'orders' => [] ];
        $orders_to_process = $orders;
    }

    $result_orders = [];

    foreach ( $orders_to_process as $order ) {
        if ( $order->status === 'draft' ) continue;

        $api_token = \SureCart\Models\ApiToken::get();
        $response  = wp_remote_get(
            "https://api.surecart.com/v1/notes?notable_id={$order->id}&notable_type=order",
            [ 'headers' => [
                'Authorization' => 'Bearer ' . $api_token,
                'Content-Type'  => 'application/json',
            ] ]
        );

        $download_note = null;
        if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
            $body  = json_decode( wp_remote_retrieve_body( $response ), true );
            $notes = $body['data'] ?? [];
            foreach ( $notes as $note ) {
                if ( ! empty( $note['metadata'] ) && isset( $note['metadata']['fulfilled_at'] ) ) {
                    $download_note = $note['metadata'];
                    break;
                }
            }
        }

        $parcel = $checkout_parcel_map[ $order->id ] ?? '';
        if ( empty( $parcel ) ) $parcel = btx_try_get_parcel_from_order( $order );

        $result_orders[] = [
            'id'                 => $order->id,
            'order_number'       => $order->number,
            'fulfillment_status' => $order->fulfillment_status,
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

        // ── Checkout tokens ──────────────────────────────────────
        const urlParams = new URLSearchParams(window.location.search);
        let tokens = [];
        if (urlParams.get('sc_order')) tokens.push(urlParams.get('sc_order'));
        try {
            const ls = JSON.parse(localStorage.getItem('scCompletedCheckouts') || '[]');
            if (Array.isArray(ls)) tokens = tokens.concat(ls);
        } catch (e) {}
        tokens = [...new Set(tokens)];

        // ── Key helpers ──────────────────────────────────────────
        const IMAGE_KEY_ORDER = ['overhead_url','north_url','east_url','south_url','west_url'];

        function isImageKey(key) {
            return key.endsWith('_url') && key !== 'kml_url' && key !== 'map_url';
        }

        function labelForKey(key) {
            const MAP = {
                overhead_url : 'Overhead Aerial',
                north_url    : 'North View',
                east_url     : 'East View',
                south_url    : 'South View',
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
            const allKeys = Object.keys(meta).filter(k => k.endsWith('_url'));
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
            detailEl.classList.add('btx-detail--open');
            window.scrollTo({ top: container.offsetTop - 24, behavior: 'smooth' });
        }

        function closeDetail() {
            detailEl.classList.remove('btx-detail--open');
            detailEl.querySelector('#btx-detail-body').innerHTML = '';
            if (listEl) listEl.style.display = '';
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
                if (res.status === 404) return true; // not ready yet — keep polling
                if (!res.ok) {
                    let msg = `Error ${res.status}`;
                    try { const e = await res.json(); if (e.message) msg += ': ' + e.message; } catch(_){}
                    container.innerHTML = `<p>${msg}</p>`;
                    return false;
                }

                const data   = await res.json();
                const orders = data.orders || [];
                if (orders.length === 0) {
                    container.innerHTML = '<p>You have no orders yet.</p>';
                    return false;
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


                // ── Rebuild list ─────────────────────────────────
                container.innerHTML = '';
                orderDataStore = [];

                const heading = document.createElement('h2');
                heading.className   = 'btx-files-heading';
                heading.textContent = 'My Files';
                container.appendChild(heading);

                // Detail panel (once, always present in DOM)
                detailEl = buildDetailPanel();
                container.appendChild(detailEl);

                listEl = document.createElement('div');
                listEl.className = 'btx-files-card';
                container.appendChild(listEl);

                let visibleCount      = 0;
                let isWaitingForFiles = false;

                for (const order of orders) {
                    const meta        = order.metadata;
                    const isFulfilled = meta && meta.fulfilled_at;
                    const isProcessing= order.fulfillment_status === 'unfulfilled';
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
                        isWaitingForFiles = true;
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

                if (isWaitingForFiles) {
                    const note = document.createElement('p');
                    note.className   = 'btx-processing-note';
                    note.textContent = 'Files are being generated. We will also email you the download links when ready.';
                    container.appendChild(note);
                }

                // ── Restore URL state (deep link / page refresh) ─
                const deepLinkId = getOrderIdFromUrl();
                if (deepLinkId) {
                    const idx = orderDataStore.findIndex(o => o.id === deepLinkId);
                    if (idx > -1) openDetail(idx);
                }

                return isWaitingForFiles && tokens.length > 0;

            } catch (err) {
                container.innerHTML = `<p>Error loading your files: ${err.message}</p>`;
                return false;
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
