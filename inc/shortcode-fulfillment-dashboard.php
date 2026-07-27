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

function btx_render_fulfillment_dashboard_script() {
    // Only inject this script on the 'dash' or 'dashboard' page
    if (!is_page(array('dash', 'dashboard'))) return;

    // Generate a nonce (will be a guest nonce if WP user is not logged in, but SureCart will authenticate the request via cookie)
    $nonce = wp_create_nonce('wp_rest');
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
        console.log("Fulfillment dashboard script loaded.");
        const container = document.getElementById('btx-fulfillment-dashboard');
        if (!container) {
            console.log("Container 'btx-fulfillment-dashboard' not found on page.");
            return;
        }
        
        const nonce = '<?php echo esc_js($nonce); ?>';
        const urlParams = new URLSearchParams(window.location.search);
        const scOrderToken = urlParams.get('sc_order');
        
        try {
            console.log("Fetching orders...", scOrderToken ? `(Using token: ${scOrderToken})` : "");
            let orders = [];
            
            const authErrorMessage = '<p>Please log in to your dashboard to view your files.</p><p style="font-size: 0.9em; color: #64748b;">(If you just purchased or are already logged in, please refresh the page to update your session.)</p>';
            
            if (scOrderToken) {
                // Fetch specific order by token
                const orderRes = await fetch(`/wp-json/surecart/v1/orders/${scOrderToken}?token=${scOrderToken}`, {
                    headers: { 'X-WP-Nonce': nonce },
                    credentials: 'same-origin'
                });
                
                if (orderRes.ok) {
                    const orderData = await orderRes.json();
                    if (orderData.data) {
                        orders = [orderData.data];
                    }
                } else {
                    // Try to parse the error message if possible
                    let errorMsg = `Status ${orderRes.status}`;
                    try {
                        const errData = await orderRes.json();
                        if (errData.message) errorMsg += ` - ${errData.message}`;
                    } catch(e) {}
                    
                    if (orderRes.status === 401 || orderRes.status === 403 || orderRes.status === 404) {
                        console.warn(`Token fetch failed (${errorMsg}), falling back to all orders...`);
                        const allOrdersRes = await fetch('/wp-json/surecart/v1/orders', {
                            headers: { 'X-WP-Nonce': nonce },
                            credentials: 'same-origin'
                        });
                        
                        if (allOrdersRes.ok) {
                            const allOrdersData = await allOrdersRes.json();
                            orders = allOrdersData.data || [];
                        } else if (allOrdersRes.status === 401 || allOrdersRes.status === 403) {
                             container.innerHTML = authErrorMessage;
                             return;
                        } else {
                            throw new Error(`Fallback fetch failed (Status: ${allOrdersRes.status})`);
                        }
                    } else {
                        throw new Error(`Token fetch failed (${errorMsg})`);
                    }
                }
            } else {
                // Fetch all orders
                const ordersRes = await fetch('/wp-json/surecart/v1/orders', {
                    headers: { 'X-WP-Nonce': nonce },
                    credentials: 'same-origin'
                });
                
                if (ordersRes.status === 401 || ordersRes.status === 403) {
                    container.innerHTML = authErrorMessage;
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
            }
            
            if (orders.length === 0) {
                container.innerHTML = '<p>You have no orders yet.</p>';
                return;
            }
            
            container.innerHTML = '<h2 style="margin-bottom:20px; color: blue;">My Files (CD Test)</h2>';
            let fileCount = 0;
            
            for (const order of orders) {
                // Skip draft or unpaid orders
                if (order.status === 'draft') continue;
                
                // 2. Fetch Notes for the order
                // If we are a guest with a token, we might need to pass the token to the notes endpoint as well, 
                // but usually notes are public if you have the order access, or maybe we append ?token=
                const tokenParam = scOrderToken ? `&token=${scOrderToken}` : '';
                const notesRes = await fetch(`/wp-json/surecart/v1/notes?notable_id=${order.id}&notable_type=order${tokenParam}`, {
                    headers: { 'X-WP-Nonce': nonce },
                    credentials: 'same-origin'
                });
                const notesData = await notesRes.json();
                const notes = notesData.data || [];
                
                // 3. Find note with download metadata
                const downloadNote = notes.find(n => n.metadata && n.metadata.fulfilled_at);
                
                const card = document.createElement('div');
                card.className = 'btx-order-card';
                
                let orderTitle = `Order #${order.order_number}`;
                
                if (downloadNote && downloadNote.metadata) {
                    fileCount++;
                    const meta = downloadNote.metadata;
                    
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
                    // Check if it's a product that yields files by looking for our snapshot mode
                    // But order line items aren't immediately accessible without expansion.
                    // For now, any unfulfilled order might be processing files.
                    fileCount++;
                    card.innerHTML = `
                        <div class="btx-order-header">
                            <h3>${orderTitle}</h3>
                            <p class="btx-status-processing">Processing your files...</p>
                        </div>
                    `;
                    container.appendChild(card);
                }
            }
            
            if (fileCount === 0) {
                container.innerHTML += '<p>No files available for your orders.</p>';
            }
            
        } catch (err) {
            console.error("Dashboard error:", err);
            container.innerHTML = '<p>Error loading your files: ' + err.message + '</p>';
        }
    });
    </script>
    <?php
}
