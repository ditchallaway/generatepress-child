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

// AJAX action to fetch a fresh nonce for cached pages
add_action('wp_ajax_btx_get_fresh_nonce', 'btx_get_fresh_nonce_callback');
add_action('wp_ajax_nopriv_btx_get_fresh_nonce', 'btx_get_fresh_nonce_callback');
function btx_get_fresh_nonce_callback() {
    wp_send_json_success(wp_create_nonce('wp_rest'));
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
    document.addEventListener("DOMContentLoaded", async function() {
        console.group("Fulfillment Dashboard Debug");
        console.log("Script loaded and initialized.");
        
        const container = document.getElementById('btx-fulfillment-dashboard');
        if (!container) {
            console.warn("Container 'btx-fulfillment-dashboard' not found on page. Exiting.");
            console.groupEnd();
            return;
        }
        
        const urlParams = new URLSearchParams(window.location.search);
        const scOrderToken = urlParams.get('sc_order');
        console.log("URL parameters detected:", { sc_order: scOrderToken });
        
        try {
            console.log("Fetching fresh nonce from admin-ajax.php...");
            const ajaxUrl = '<?php echo admin_url("admin-ajax.php"); ?>';
            const fd = new FormData();
            fd.append('action', 'btx_get_fresh_nonce');
            
            const nonceRes = await fetch(ajaxUrl, { 
                method: 'POST', 
                body: fd,
                credentials: 'same-origin'
            });
            const nonceData = await nonceRes.json();
            const nonce = nonceData.success ? nonceData.data : '';
            console.log("Nonce fetched successfully:", nonce ? "Yes (length " + nonce.length + ")" : "No");
            
            let orders = [];
            const authErrorMessage = '<p>Please log in to your dashboard to view your files.</p><p style="font-size: 0.9em; color: #64748b;">(If you just purchased or are already logged in, please refresh the page to update your session.)</p>';
            
            if (scOrderToken) {
                console.log(`Attempting to fetch specific order by token: ${scOrderToken}`);
                const orderRes = await fetch(`/wp-json/surecart/v1/orders/${scOrderToken}?token=${scOrderToken}`, {
                    headers: { 'X-WP-Nonce': nonce },
                    credentials: 'same-origin'
                });
                
                if (orderRes.ok) {
                    const orderData = await orderRes.json();
                    if (orderData.data) {
                        orders = [orderData.data];
                        console.log("Successfully fetched order via token:", orders[0]);
                    }
                } else {
                    let errorMsg = `Status ${orderRes.status}`;
                    try {
                        const errData = await orderRes.json();
                        if (errData.message) errorMsg += ` - ${errData.message}`;
                    } catch(e) {}
                    
                    console.warn(`Token fetch failed (${errorMsg}). Checking for fallback...`);
                    
                    if (orderRes.status === 401 || orderRes.status === 403 || orderRes.status === 404) {
                        console.log("Falling back to fetch ALL orders for current logged-in user...");
                        const allOrdersRes = await fetch('/wp-json/surecart/v1/orders', {
                            headers: { 'X-WP-Nonce': nonce },
                            credentials: 'same-origin'
                        });
                        
                        if (allOrdersRes.ok) {
                            const allOrdersData = await allOrdersRes.json();
                            orders = allOrdersData.data || [];
                            console.log(`Fallback successful. Retrieved ${orders.length} orders.`, orders);
                        } else if (allOrdersRes.status === 401 || allOrdersRes.status === 403) {
                             console.warn(`Fallback fetch denied (Status ${allOrdersRes.status}). User likely logged out or nonce invalid.`);
                             container.innerHTML = authErrorMessage;
                             console.groupEnd();
                             return;
                        } else {
                            throw new Error(`Fallback fetch failed (Status: ${allOrdersRes.status})`);
                        }
                    } else {
                        throw new Error(`Token fetch failed (${errorMsg})`);
                    }
                }
            } else {
                console.log("No token provided. Fetching all orders for current logged-in user...");
                const ordersRes = await fetch('/wp-json/surecart/v1/orders', {
                    headers: { 'X-WP-Nonce': nonce },
                    credentials: 'same-origin'
                });
                
                if (ordersRes.status === 401 || ordersRes.status === 403) {
                    console.warn(`Fetch all orders denied (Status ${ordersRes.status}). User likely logged out or nonce invalid.`);
                    container.innerHTML = authErrorMessage;
                    console.groupEnd();
                    return;
                }
                
                if (!ordersRes.ok) {
                    let errorMsg = `Status ${ordersRes.status}`;
                    try {
                        const errData = await ordersRes.json();
                        if (errData.message) errorMsg += ` - ${errData.message}`;
                    } catch(e) {}
                    throw new Error(`Failed to fetch orders (${errorMsg})`);
                }
                
                const ordersData = await ordersRes.json();
                orders = ordersData.data || [];
                console.log(`Successfully fetched ${orders.length} orders.`, orders);
            }
            
            if (orders.length === 0) {
                console.log("No orders found to display.");
                container.innerHTML = '<p>You have no orders yet.</p>';
                console.groupEnd();
                return;
            }
            
            container.innerHTML = '<h2 style="margin-bottom:20px; color: blue;">My Files (CD Test)</h2>';
            let fileCount = 0;
            
            for (const order of orders) {
                console.groupCollapsed(`Processing Order #${order.order_number}`);
                console.log("Order Data:", order);
                
                // Skip draft or unpaid orders
                if (order.status === 'draft') {
                    console.log("Skipping draft order.");
                    console.groupEnd();
                    continue;
                }
                
                // 2. Fetch Notes for the order
                const tokenParam = scOrderToken ? `&token=${scOrderToken}` : '';
                console.log(`Fetching notes for order ID: ${order.id}...`);
                
                const notesRes = await fetch(`/wp-json/surecart/v1/notes?notable_id=${order.id}&notable_type=order${tokenParam}`, {
                    headers: { 'X-WP-Nonce': nonce },
                    credentials: 'same-origin'
                });
                
                if (!notesRes.ok) {
                    console.warn(`Failed to fetch notes for order ${order.id} (Status ${notesRes.status})`);
                    console.groupEnd();
                    continue;
                }
                
                const notesData = await notesRes.json();
                const notes = notesData.data || [];
                console.log(`Retrieved ${notes.length} notes.`, notes);
                
                // 3. Find note with download metadata
                const downloadNote = notes.find(n => n.metadata && n.metadata.fulfilled_at);
                
                const card = document.createElement('div');
                card.className = 'btx-order-card';
                
                let orderTitle = `Order #${order.order_number}`;
                
                if (downloadNote && downloadNote.metadata) {
                    fileCount++;
                    const meta = downloadNote.metadata;
                    console.log("Valid fulfillment note found! Metadata:", meta);
                    
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
                    console.log("No fulfillment note found, but order is unfulfilled. Showing processing message.");
                    fileCount++;
                    card.innerHTML = `
                        <div class="btx-order-header">
                            <h3>${orderTitle}</h3>
                            <p class="btx-status-processing">Processing your files...</p>
                        </div>
                    `;
                    container.appendChild(card);
                } else {
                    console.log("Order is fulfilled but has no download note. Skipping display.");
                }
                
                console.groupEnd();
            }
            
            console.log(`Total files/processing orders displayed: ${fileCount}`);
            if (fileCount === 0) {
                container.innerHTML += '<p>No files available for your orders.</p>';
            }
            
            console.groupEnd();
        } catch (err) {
            console.error("Dashboard error exception:", err);
            container.innerHTML = '<p>Error loading your files: ' + err.message + '</p>';
            console.groupEnd();
        }
    });
    </script>
    <?php
}
