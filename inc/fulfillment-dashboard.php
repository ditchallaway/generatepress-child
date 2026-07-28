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

function btx_get_fulfillment_files($request) {
    $token = $request->get_param('token');
    
    // We must have the SureCart plugin active
    if (!class_exists('\SureCart\Models\Checkout')) {
        return new WP_Error('surecart_missing', 'SureCart plugin is not active.', array('status' => 500));
    }
    
    $orders_to_process = [];
    
    if (!empty($token)) {
        // 1. Fetch by checkout token (bypasses WordPress authentication entirely)
        $checkout = \SureCart\Models\Checkout::find($token);
        if (!$checkout) {
            return new WP_Error('invalid_token', 'Invalid checkout token.', array('status' => 404));
        }
        
        $order_id = is_object($checkout->order) ? $checkout->order->id : $checkout->order;
        if (empty($order_id)) {
            return new WP_Error('no_order', 'Order not created yet.', array('status' => 404));
        }
        
        $order = \SureCart\Models\Order::find($order_id);
        if (!$order) {
            return new WP_Error('no_order', 'Order not found.', array('status' => 404));
        }
        $orders_to_process[] = $order;
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
        
        $result_orders[] = array(
            'id' => $order->id,
            'order_number' => $order->number,
            'fulfillment_status' => $order->fulfillment_status,
            'metadata' => $download_note
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
        .btx-dashboard-container {
            margin-top: 20px;
            font-family: sans-serif;
        }
        .btx-order-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .btx-order-header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btx-order-header h3 {
            margin: 0;
            font-size: 1.25rem;
            color: #1e293b;
        }
        .btx-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .btx-file-card {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            background: #f8fafc;
        }
        .btx-file-card strong {
            display: block;
            margin-bottom: 12px;
            color: #334155;
            font-size: 0.95rem;
        }
        .btx-btn {
            display: inline-block;
            background: #2563eb;
            color: #ffffff;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9em;
            margin: 4px;
            transition: background 0.2s;
            font-weight: 500;
        }
        .btx-btn:hover {
            background: #1d4ed8;
            color: #ffffff;
        }
        .btx-btn-secondary {
            background: #475569;
        }
        .btx-btn-secondary:hover {
            background: #334155;
        }
        .btx-status-processing {
            color: #d97706;
            font-weight: 600;
            margin: 0;
        }
        .btx-status-processing-subtitle {
            font-size: 0.85em;
            color: #64748b;
            margin-top: 4px;
        }
        .btx-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(217, 119, 6, 0.3);
            border-radius: 50%;
            border-top-color: #d97706;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
            vertical-align: middle;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        /* Mobile adjustments */
        @media (max-width: 600px) {
            .btx-order-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .btx-order-header small {
                margin-top: 4px;
            }
        }
    </style>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        console.group("Fulfillment Dashboard Debug");
        console.log("Script loaded and initialized (Server-Side Proxy Mode).");
        
        const container = document.getElementById('btx-fulfillment-dashboard');
        if (!container) {
            console.warn("Container 'btx-fulfillment-dashboard' not found on page. Exiting.");
            console.groupEnd();
            return;
        }
        
        const urlParams = new URLSearchParams(window.location.search);
        const scOrderToken = urlParams.get('sc_order');
        console.log("URL parameters detected:", { sc_order: scOrderToken });
        
        const pollInterval = 5000; // 5 seconds
        const maxPolls = 60; // 5 minutes max
        let pollCount = 0;
        
        async function fetchAndRenderFiles() {
            try {
                console.log(`Fetching orders via custom endpoint... Token: ${scOrderToken || 'None (Using WP Session)'}`);
                const endpoint = scOrderToken ? `/wp-json/btx/v1/fulfillment?token=${scOrderToken}` : '/wp-json/btx/v1/fulfillment';
                
                // We send credentials so if they don't have a token, WordPress knows who they are.
                const res = await fetch(endpoint, { credentials: 'same-origin' });
                
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
                
                container.innerHTML = '<h2 style="margin-bottom:20px; color: #1e293b;">My Files</h2>';
                let fileCount = 0;
                let isWaitingForFiles = false;
                
                for (const order of orders) {
                    const card = document.createElement('div');
                    card.className = 'btx-order-card';
                    let orderTitle = `Order #${order.order_number}`;
                    
                    if (order.metadata && order.metadata.fulfilled_at) {
                        fileCount++;
                        const meta = order.metadata;
                        console.log("Valid fulfillment metadata found!", meta);
                        
                        let filesHtml = '';
                        
                        if (meta.overhead_url) {
                            filesHtml += `
                                <div class="btx-file-card">
                                    <strong>Overhead Aerial</strong>
                                    <a href="${meta.overhead_url}" class="btx-btn" target="_blank" download>Print Size</a>
                                </div>
                            `;
                        }
                        if (meta.map_url) {
                            filesHtml += `
                                <div class="btx-file-card">
                                    <strong>Static Context Map</strong>
                                    <a href="${meta.map_url}" class="btx-btn" target="_blank" download>Print Size</a>
                                </div>
                            `;
                        }
                        if (meta.kml_url) {
                            filesHtml += `
                                <div class="btx-file-card">
                                    <strong>Boundary Coordinates</strong>
                                    <a href="${meta.kml_url}" class="btx-btn" target="_blank" download>Download KML</a>
                                </div>
                            `;
                        }
                        
                        card.innerHTML = `
                            <div class="btx-order-header">
                                <h3>${orderTitle}</h3>
                                <small>Fulfilled: ${new Date(meta.fulfilled_at).toLocaleDateString()}</small>
                            </div>
                            <div class="btx-gallery-grid">
                                ${filesHtml}
                            </div>
                        `;
                        container.appendChild(card);
                        
                    } else if (order.fulfillment_status === 'unfulfilled') {
                        console.log("Order is unfulfilled. Waiting for files...");
                        isWaitingForFiles = true;
                        fileCount++;
                        card.innerHTML = `
                            <div class="btx-order-header">
                                <h3>${orderTitle}</h3>
                                <div>
                                    <p class="btx-status-processing"><span class="btx-spinner"></span>Generating your custom files...</p>
                                    <p class="btx-status-processing-subtitle">This usually takes 1-3 minutes. We will also email you the links when they are ready.</p>
                                </div>
                            </div>
                        `;
                        container.appendChild(card);
                    }
                }
                
                if (fileCount === 0) {
                    container.innerHTML += '<p>No files available for your orders.</p>';
                }
                
                // If we are waiting for files and we have a token, we should poll.
                // If they are just looking at their historical dashboard without a token, no need to poll.
                if (isWaitingForFiles && scOrderToken) {
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
