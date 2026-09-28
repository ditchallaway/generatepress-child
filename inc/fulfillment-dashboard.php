<?php
/**
 * Shortcode for the Custom Fulfillment Dashboard block
 * Usage: [btx_fulfillment_dashboard]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// We will hook this into the footer so it runs automatically without needing a shortcode!
add_action('wp_footer', 'btx_render_fulfillment_dashboard_script');

// Register custom REST API endpoint for fetching SureCart files
add_action('rest_api_init', function () {
    register_rest_route('btx/v1', '/fulfillment', array(
        'methods' => 'GET',
        'callback' => 'btx_get_fulfillment_files',
        'permission_callback' => '__return_true' // We handle auth internally via token or WP user
    ));
});

/**
 * Extract parcel number from a checkout's metadata.
 */
function btx_extract_parcel_from_metadata($checkout) {
    if (!isset($checkout->metadata)) return '';

    $meta = $checkout->metadata;
    if (is_object($meta) && isset($meta->parcel)) {
        return $meta->parcel;
    }
    if (is_array($meta) && isset($meta['parcel'])) {
        return $meta['parcel'];
    }
    return '';
}

/**
 * Try to get parcel number from an order's checkout (if already expanded).
 * Does NOT make extra API calls — only reads data already on the order object.
 */
function btx_try_get_parcel_from_order($order) {
    try {
        if (isset($order->checkout) && is_object($order->checkout)) {
            return btx_extract_parcel_from_metadata($order->checkout);
        }
    } catch (\Exception $e) {
        // Silently fail — parcel is optional
    }
    return '';
}

function btx_get_fulfillment_files($request) {
    // Get comma-separated tokens or single token
    $tokens_str = $request->get_param('tokens');
    $tokens = [];
    if (!empty($tokens_str)) {
        $tokens = array_filter(array_map('trim', explode(',', $tokens_str)));
    }
    $token = $request->get_param('token');
    if (!empty($token)) {
        $tokens[] = $token;
    }
    $tokens = array_unique($tokens);
    
    // We must have the SureCart plugin active
    if (!class_exists('\\SureCart\\Models\\Checkout')) {
        return new WP_Error('surecart_missing', 'SureCart plugin is not active.', array('status' => 500));
    }
    
    $orders_to_process = [];
    $checkout_parcel_map = [];
    
    if (!empty($tokens)) {
        // 1. Fetch by checkout tokens (bypasses WordPress authentication entirely)
        foreach ($tokens as $t) {
            $checkout = \SureCart\Models\Checkout::find($t);
            if ($checkout) {
                // Extract parcel from checkout metadata
                $parcel = btx_extract_parcel_from_metadata($checkout);

                $order_id = is_object($checkout->order) ? $checkout->order->id : $checkout->order;
                if (!empty($order_id)) {
                    $order = \SureCart\Models\Order::find($order_id);
                    if ($order) {
                        $checkout_parcel_map[$order->id] = $parcel;
                        $orders_to_process[] = $order;
                    }
                }
            }
        }
        if (empty($orders_to_process)) {
            return new WP_Error('no_order', 'Orders not found or not created yet.', array('status' => 404));
        }
    } else {
        // 2. Fetch by logged in user
        $user_id = get_current_user_id();
        if (!$user_id) {
            return new WP_Error('unauthorized', 'You must be logged in to view all files.', array('status' => 401));
        }
        
        $current_user = wp_get_current_user();
        $orders = \SureCart\Models\Order::where('customer.email', $current_user->user_email)
            ->orderBy('created_at', 'desc')
            ->get();
            
        if (empty($orders)) {
            return array('orders' => []);
        }
        $orders_to_process = $orders;
    }
    
    $result_orders = [];
    
    foreach ($orders_to_process as $order) {
        if ($order->status === 'draft') continue;
        
        $api_token = \SureCart\Models\ApiToken::get();
        $response = wp_remote_get("https://api.surecart.com/v1/notes?notable_id={$order->id}&notable_type=order", [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_token,
                'Content-Type'  => 'application/json'
            ]
        ]);
        
        $download_note = null;
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $body = json_decode(wp_remote_retrieve_body($response), true);
            $notes = isset($body['data']) ? $body['data'] : [];
            
            foreach ($notes as $note) {
                if (!empty($note['metadata']) && isset($note['metadata']['fulfilled_at'])) {
                    $download_note = $note['metadata'];
                    break;
                }
            }
        }

        // Get parcel: prefer the checkout map (token path), fall back to order's checkout (if expanded)
        $parcel = $checkout_parcel_map[$order->id] ?? '';
        if (empty($parcel)) {
            $parcel = btx_try_get_parcel_from_order($order);
        }
        
        $result_orders[] = array(
            'id' => $order->id,
            'order_number' => $order->number,
            'fulfillment_status' => $order->fulfillment_status,
            'metadata' => $download_note,
            'parcel' => $parcel,
            'created_at' => $order->created_at ?? null,
        );
    }
    
    return array('orders' => $result_orders);
}

function btx_render_fulfillment_dashboard_script() {
    // Only inject this script on the 'dash' or 'dashboard' page
    if (!is_page(array('dash', 'dashboard'))) return;

    ?>
    <!-- The script will populate any div with id="btx-fulfillment-dashboard" on the page -->
    
    <style>
        /* ===== Fulfillment Dashboard: Files List ===== */

        .btx-files-heading {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            font-family: inherit;
        }

        /* Card container — matches sc-card no-padding */
        .btx-files-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        /* List row — matches sc-stacked-list-row with 4 columns */
        .btx-files-row {
            display: grid;
            grid-template-columns: 2fr 1.5fr 1fr 1fr;
            align-items: center;
            padding: 16px 20px;
            border-top: 1px solid #e2e8f0;
            cursor: pointer;
            transition: background-color 0.15s ease;
            gap: 12px;
        }
        .btx-files-row:first-child {
            border-top: none;
        }
        .btx-files-row:hover {
            background-color: #f8fafc;
        }

        /* Non-clickable processing rows */
        .btx-files-row--processing {
            cursor: default;
        }
        .btx-files-row--processing:hover {
            background-color: transparent;
        }

        /* Row cells */
        .btx-files-row__id {
            font-weight: 500;
            color: #1e293b;
            font-size: 0.95rem;
        }
        .btx-files-row__date {
            color: var(--sc-color-gray-500, #64748b);
            font-size: 0.9rem;
        }
        .btx-files-row__count {
            color: var(--sc-color-gray-500, #64748b);
            font-size: 0.9rem;
        }
        .btx-files-row__status {
            text-align: right;
        }

        /* Badges — matches sc-order-status-badge appearance */
        .btx-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            line-height: 1.5;
            white-space: nowrap;
        }
        .btx-badge--fulfilled {
            background-color: #dcfce7;
            color: #166534;
        }
        .btx-badge--processing {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* Spinner for processing orders */
        .btx-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(217, 119, 6, 0.3);
            border-radius: 50%;
            border-top-color: #d97706;
            animation: btx-spin 1s ease-in-out infinite;
            margin-right: 6px;
            vertical-align: middle;
        }
        @keyframes btx-spin {
            to { transform: rotate(360deg); }
        }

        /* Note text below list for processing orders */
        .btx-processing-note {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 12px;
        }

        /* ===== Download Modal ===== */
        .btx-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }
        .btx-modal-overlay.btx-active {
            opacity: 1;
            visibility: visible;
        }
        .btx-modal {
            background: #fff;
            border-radius: 8px;
            width: 90%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transform: translateY(12px);
            transition: transform 0.2s ease;
        }
        .btx-modal-overlay.btx-active .btx-modal {
            transform: translateY(0);
        }
        .btx-modal__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .btx-modal__title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
        }
        .btx-modal__close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #94a3b8;
            padding: 0 0 0 12px;
            line-height: 1;
            transition: color 0.15s;
        }
        .btx-modal__close:hover {
            color: #1e293b;
            background: none;
        }
        .btx-modal__subtitle {
            padding: 12px 24px 0;
            margin: 0;
            font-size: 0.85rem;
            color: #64748b;
        }
        .btx-modal__body {
            padding: 16px 24px 24px;
        }

        /* Download items inside modal */
        .btx-download-item {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #1e293b;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }
        .btx-download-item:last-child {
            margin-bottom: 0;
        }
        .btx-download-item:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
            text-decoration: none;
        }
        .btx-download-icon {
            font-size: 1.3rem;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .btx-download-label {
            flex: 1;
            font-weight: 500;
            font-size: 0.95rem;
        }
        .btx-download-action {
            color: #2563eb;
            font-size: 0.85rem;
            font-weight: 500;
            flex-shrink: 0;
            margin-left: 12px;
        }

        /* Mobile adjustments — stack to 2-column on small screens */
        @media (max-width: 600px) {
            .btx-files-row {
                grid-template-columns: 1fr 1fr;
                gap: 4px 12px;
                padding: 14px 16px;
            }
            .btx-files-row__status {
                text-align: left;
            }
            .btx-modal {
                width: 95%;
            }
        }
    </style>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        console.group("Fulfillment Dashboard Debug");
        console.log("Script loaded and initialized (Server-Side Proxy Mode with LocalStorage Fallback).");
        
        const container = document.getElementById('btx-fulfillment-dashboard');
        if (!container) {
            console.warn("Container 'btx-fulfillment-dashboard' not found on page. Exiting.");
            console.groupEnd();
            return;
        }
        
        const urlParams = new URLSearchParams(window.location.search);
        let tokens = [];
        if (urlParams.get('sc_order')) tokens.push(urlParams.get('sc_order'));
        
        // Fallback to SureCart's localStorage if no token in URL, or just to show recent orders
        try {
            const lsCheckouts = JSON.parse(localStorage.getItem('scCompletedCheckouts') || '[]');
            if (Array.isArray(lsCheckouts)) {
                tokens = tokens.concat(lsCheckouts);
            }
        } catch(e) {}
        
        // Remove duplicates
        tokens = [...new Set(tokens)];
        console.log("Tokens detected:", tokens);
        
        const pollInterval = 5000; // 5 seconds
        const maxPolls = 60; // 5 minutes max
        let pollCount = 0;
        
        const wpNonce = "<?php echo esc_js(wp_create_nonce('wp_rest')); ?>";

        // ── Data store for modal access ──
        const orderDataStore = [];

        // ── Create modal element (once) ──
        const modalOverlay = document.createElement('div');
        modalOverlay.className = 'btx-modal-overlay';
        modalOverlay.id = 'btx-modal';
        modalOverlay.innerHTML = `
            <div class="btx-modal">
                <div class="btx-modal__header">
                    <h3 class="btx-modal__title"></h3>
                    <button class="btx-modal__close" aria-label="Close">&times;</button>
                </div>
                <p class="btx-modal__subtitle"></p>
                <div class="btx-modal__body"></div>
            </div>
        `;
        document.body.appendChild(modalOverlay);

        // ── Helpers for building download links from metadata ──

        /**
         * Human-readable label for a metadata URL key.
         * Keys follow the pattern: overhead_url, north_url, kml_url, shot_2_url, etc.
         */
        function labelForKey(key) {
            const map = {
                'overhead_url': 'Overhead Aerial',
                'north_url':    'North View',
                'east_url':     'East View',
                'south_url':    'South View',
                'west_url':     'West View',
                'kml_url':      'Boundary Coordinates (KML)',
                'map_url':      'Static Context Map'
            };
            if (map[key]) return map[key];
            // Fallback for shot_N_url keys
            const shotMatch = key.match(/^shot_(\d+)_url$/);
            if (shotMatch) return `Photo ${shotMatch[1]}`;
            // Generic fallback: strip _url, capitalize
            return key.replace(/_url$/, '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        }

        /** Icon for a metadata URL key. */
        function iconForKey(key) {
            if (key === 'kml_url') return '📍';
            if (key === 'map_url') return '🗺️';
            return '🖼️';
        }

        /**
         * Extract all downloadable URL entries from the metadata object.
         * Returns an array of { key, url, label, icon }.
         * Ordered: overhead first, then named shots, then numbered shots, then kml/map.
         */
        function extractDownloads(meta) {
            if (!meta) return [];

            const downloads = [];
            const ordering = ['overhead_url'];
            const namedShots = ['north_url', 'east_url', 'south_url', 'west_url'];
            const tail = ['map_url', 'kml_url'];

            // Collect all *_url keys present in the metadata
            const allKeys = Object.keys(meta).filter(k => k.endsWith('_url'));

            // Numbered shot keys (shot_2_url, shot_3_url, …) sorted numerically
            const numberedKeys = allKeys
                .filter(k => /^shot_\d+_url$/.test(k))
                .sort((a, b) => {
                    const na = parseInt(a.match(/\d+/)[0], 10);
                    const nb = parseInt(b.match(/\d+/)[0], 10);
                    return na - nb;
                });

            // Build ordered key list
            const orderedKeys = [
                ...ordering.filter(k => allKeys.includes(k)),
                ...namedShots.filter(k => allKeys.includes(k)),
                ...numberedKeys,
                ...tail.filter(k => allKeys.includes(k)),
            ];

            // Catch any remaining keys we haven't handled
            const handled = new Set(orderedKeys);
            const remaining = allKeys.filter(k => !handled.has(k));

            const finalKeys = [...orderedKeys, ...remaining];

            for (const key of finalKeys) {
                downloads.push({
                    key,
                    url: meta[key],
                    label: labelForKey(key),
                    icon: iconForKey(key)
                });
            }

            return downloads;
        }

        // ── Modal helpers ──
        function openModal(index) {
            const data = orderDataStore[index];
            const modal = document.getElementById('btx-modal');

            modal.querySelector('.btx-modal__title').textContent = data.identifier;
            modal.querySelector('.btx-modal__subtitle').textContent =
                'Fulfilled ' + data.fulfilledDate;

            let html = '';
            for (const dl of data.downloads) {
                html += `<a href="${dl.url}" class="btx-download-item" target="_blank" download>
                    <span class="btx-download-icon">${dl.icon}</span>
                    <span class="btx-download-label">${dl.label}</span>
                    <span class="btx-download-action">Download ↓</span>
                </a>`;
            }

            modal.querySelector('.btx-modal__body').innerHTML = html;
            modal.classList.add('btx-active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('btx-modal');
            modal.classList.remove('btx-active');
            document.body.style.overflow = '';
        }

        // Close on X button
        modalOverlay.querySelector('.btx-modal__close').addEventListener('click', closeModal);
        // Close on backdrop click
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) closeModal();
        });
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        // ── Fetch & render ──
        async function fetchAndRenderFiles() {
            try {
                console.log(`Fetching orders via custom endpoint... Tokens: ${tokens.join(',') || 'None (Using WP Session)'}`);
                const endpoint = tokens.length > 0 
                    ? `/wp-json/btx/v1/fulfillment?tokens=${encodeURIComponent(tokens.join(','))}` 
                    : '/wp-json/btx/v1/fulfillment';
                
                // We send credentials and X-WP-Nonce so WordPress REST API accepts the cookie session
                const res = await fetch(endpoint, { 
                    credentials: 'same-origin',
                    headers: {
                        'X-WP-Nonce': wpNonce
                    }
                });
                
                if (res.status === 401) {
                    console.warn("Fetch denied (Status 401). User is logged out and no token provided.");
                    container.innerHTML = '<p>Please log in to your dashboard to view your files.</p><p style="font-size: 0.9em; color: #64748b;">(If you just purchased or are already logged in, please refresh the page to update your session.)</p>';
                    return false; // Stop polling
                }
                
                if (!res.ok) {
                    let errorMsg = `Status ${res.status}`;
                    try {
                        const errData = await res.json();
                        if (errData.message) errorMsg += ` - ${errData.message}`;
                    } catch(e) {}
                    console.error("API error:", errorMsg);
                    
                    if (res.status === 404) {
                        // Order or checkout not found yet (could be asynchronous delay from SureCart)
                        return true; // Continue polling
                    }
                    
                    container.innerHTML = `<p>Error loading your files: ${errorMsg}</p>`;
                    return false; // Stop polling
                }
                
                const data = await res.json();
                const orders = data.orders || [];
                console.log(`Successfully fetched ${orders.length} orders.`, orders);
                
                if (orders.length === 0) {
                    container.innerHTML = '<p>You have no orders yet.</p>';
                    return false;
                }

                // ── Build the list ──
                container.innerHTML = '';
                orderDataStore.length = 0;

                const heading = document.createElement('h2');
                heading.className = 'btx-files-heading';
                heading.textContent = 'My Files';
                container.appendChild(heading);

                const card = document.createElement('div');
                card.className = 'btx-files-card';
                container.appendChild(card);

                let visibleCount = 0;
                let isWaitingForFiles = false;
                
                for (const order of orders) {
                    const meta = order.metadata;
                    const isFulfilled = meta && meta.fulfilled_at;
                    const isProcessing = order.fulfillment_status === 'unfulfilled';

                    if (!isFulfilled && !isProcessing) continue;

                    visibleCount++;
                    const row = document.createElement('div');
                    row.className = 'btx-files-row';

                    // Prefer parcel number over order number for the identifier
                    const identifier = order.parcel
                        ? `Parcel ${order.parcel}`
                        : `Order #${order.order_number}`;

                    if (isFulfilled) {
                        // Extract downloads dynamically from metadata keys
                        const downloads = extractDownloads(meta);

                        const fulfilledDate = new Date(meta.fulfilled_at)
                            .toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });

                        row.innerHTML = `
                            <div class="btx-files-row__id">${identifier}</div>
                            <div class="btx-files-row__date">${fulfilledDate}</div>
                            <div class="btx-files-row__count">${downloads.length} file${downloads.length !== 1 ? 's' : ''}</div>
                            <div class="btx-files-row__status">
                                <span class="btx-badge btx-badge--fulfilled">Fulfilled</span>
                            </div>
                        `;

                        const dataIndex = orderDataStore.length;
                        orderDataStore.push({ identifier, meta, fulfilledDate, downloads });
                        row.addEventListener('click', function() { openModal(dataIndex); });

                        console.log("Fulfilled order rendered:", identifier, meta);

                    } else if (isProcessing) {
                        isWaitingForFiles = true;
                        row.classList.add('btx-files-row--processing');

                        row.innerHTML = `
                            <div class="btx-files-row__id">${identifier}</div>
                            <div class="btx-files-row__date"><span class="btx-spinner"></span>Generating...</div>
                            <div class="btx-files-row__count">ETA: 1-3 min</div>
                            <div class="btx-files-row__status">
                                <span class="btx-badge btx-badge--processing">Processing</span>
                            </div>
                        `;

                        console.log("Processing order rendered:", identifier);
                    }

                    card.appendChild(row);
                }
                
                if (visibleCount === 0) {
                    container.innerHTML += '<p>No files available for your orders.</p>';
                }

                // Show a helpful note if files are still being generated
                if (isWaitingForFiles) {
                    const note = document.createElement('p');
                    note.className = 'btx-processing-note';
                    note.textContent = 'Files are being generated. We will also email you the download links when they are ready.';
                    container.appendChild(note);
                }
                
                // If we are waiting for files and we have tokens, we should poll.
                // If they are just looking at their historical dashboard without tokens, no need to poll.
                if (isWaitingForFiles && tokens.length > 0) {
                    return true; // Continue polling
                }
                
                return false; // Stop polling
                
            } catch (err) {
                console.error("Dashboard exception:", err);
                container.innerHTML = '<p>Error loading your files: ' + err.message + '</p>';
                return false;
            }
        }
        
        async function runPoller() {
            const shouldContinue = await fetchAndRenderFiles();
            if (shouldContinue) {
                pollCount++;
                if (pollCount < maxPolls) {
                    console.log(`Scheduling next poll in ${pollInterval/1000}s (Poll ${pollCount}/${maxPolls})`);
                    setTimeout(runPoller, pollInterval);
                } else {
                    console.log("Max polls reached. Stopping.");
                }
            } else {
                console.groupEnd();
            }
        }
        
        // Start execution
        runPoller();
    });
    </script>
    <?php
}
