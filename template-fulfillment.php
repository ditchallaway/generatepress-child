<?php
/**
 * Template Name: Fulfillment Page
 */

// ── Auth Gate ─────────────────────────────────────────────────────
// SureCart customers have real WP accounts (role: sc_customer).
// We trust the WP auth cookie — no need for a 'cust' URL parameter.
$current_user_id = get_current_user_id();

if ( $current_user_id === 0 ) {
    // Not logged in — send to WP login and redirect back here afterwards
    auth_redirect();
    exit;
}

// ── URL Parameters ────────────────────────────────────────────────
// Order ID arrives as 'sc_order' (direct from checkout) or 'order' (from dashboard interceptor)
$order_id = '';
if ( ! empty( $_GET['sc_order'] ) ) {
    $order_id = sanitize_text_field( $_GET['sc_order'] );
} elseif ( ! empty( $_GET['order'] ) ) {
    $order_id = sanitize_text_field( $_GET['order'] );
}

// Pack type: single (overhead_only), double (overhead_north), full
$pack = isset( $_GET['pack'] ) ? sanitize_text_field( $_GET['pack'] ) : '';

get_header(); ?>

<div id="primary" <?php if (function_exists('generate_do_element_classes')) { generate_do_element_classes('content'); } else { echo 'class="content-area"'; } ?>>
	<main id="main" <?php if (function_exists('generate_do_element_classes')) { generate_do_element_classes('main'); } else { echo 'class="site-main"'; } ?>>
        <div class="inside-article" style="padding: 40px 20px;">
            <?php
            // Output page content if there is any
            while ( have_posts() ) : the_post();
                the_content();
            endwhile;
            ?>
            <!-- Container for the graphics -->
            <div class="moonshot-gallery-container"></div>
        </div>
	</main>
</div>

<?php 
if (function_exists('generate_construct_sidebars')) {
    generate_construct_sidebars();
}
get_footer(); 
?>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const custId  = "<?php echo esc_js( $current_user_id ); ?>";
    const orderId = "<?php echo esc_js( $order_id ); ?>";
    const nonce   = "<?php echo esc_js( wp_create_nonce('wp_rest') ); ?>";

    const container = document.querySelector(".moonshot-gallery-container");
    if (!container || !orderId) {
        if (container && !orderId) {
            container.innerHTML = `
                <div style="text-align: center; padding: 60px 20px; background: var(--base-3, #f5f5f5); border-radius: 12px;">
                    <h3 style="margin-bottom: 15px;">Missing Order ID</h3>
                    <p>We couldn't find your order. Please return to your dashboard and use the download link.</p>
                </div>`;
        }
        return;
    }

    // Polling logic
    let pollAttempts = 0;
    const maxPolls = 60; // 5 minutes at 5s intervals

    checkFulfillment();

    async function checkFulfillment() {
        try {
            const res = await fetch(`/wp-json/surecart/v1/notes?notable_id=${orderId}&notable_type=order`, {
                headers: { 'X-WP-Nonce': nonce }
            });
            
            if (!res.ok) throw new Error('Network error');
            
            const data = await res.json();
            const notes = data.data || [];
            const downloadNote = notes.find(n => n.metadata && n.metadata.fulfilled_at);
            
            if (downloadNote && downloadNote.metadata) {
                renderGallery(downloadNote.metadata);
            } else {
                pollAttempts++;
                if (pollAttempts < maxPolls) {
                    showWaiting();
                    setTimeout(checkFulfillment, 5000);
                } else {
                    showTimeout();
                }
            }
        } catch (e) {
            console.error(e);
            pollAttempts++;
            if (pollAttempts < maxPolls) {
                showWaiting();
                setTimeout(checkFulfillment, 5000);
            } else {
                showError();
            }
        }
    }

    function showWaiting() {
        container.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; background: var(--base-3, #f5f5f5); border-radius: 12px;">
                <h3 style="margin-bottom: 15px;">Generating your graphics...</h3>
                <p>We are rendering your custom assets right now. Please wait, this may take up to 2 minutes.</p>
                <div style="margin-top: 20px;">
                    <div style="display:inline-block; width: 40px; height: 40px; border: 4px solid #ccc; border-top-color: #1e73be; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                </div>
            </div>
            <style>
                @keyframes spin { to { transform: rotate(360deg); } }
            </style>`;
    }

    function showTimeout() {
        container.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; background: #fff3cd; border: 1px solid #ffe69c; border-radius: 12px;">
                <h3 style="margin-bottom: 15px; color: #856404;">Taking longer than expected</h3>
                <p style="color: #856404;">Your files are still processing. You can safely close this page. We'll email you the files when they are ready.</p>
            </div>`;
    }

    function showError() {
        container.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 12px;">
                <h3 style="margin-bottom: 15px; color: #721c24;">Connection Error</h3>
                <p style="color: #721c24;">We are having trouble checking your order status. Please check your email or dashboard later.</p>
            </div>`;
    }

    function renderGallery(meta) {
        const ts = Date.now();
        let filesHtml = '';
        
        if (meta.overhead_url) {
            filesHtml += `
                <div class="moonshot-item">
                    <img src="${meta.overhead_url}?t=${ts}" alt="Overhead Image" />
                    <div style="padding: 10px 15px 0; font-weight: bold;">Overhead Aerial</div>
                    <div class="moonshot-actions">
                        <a href="${meta.overhead_url}" class="moonshot-btn" target="_blank" download>Print Size</a>
                    </div>
                </div>
            `;
        }
        
        if (meta.map_url) {
            filesHtml += `
                <div class="moonshot-item">
                    <img src="${meta.map_url}?t=${ts}" alt="Static Map" />
                    <div style="padding: 10px 15px 0; font-weight: bold;">Context Map</div>
                    <div class="moonshot-actions">
                        <a href="${meta.map_url}" class="moonshot-btn" target="_blank" download>Print Size</a>
                    </div>
                </div>
            `;
        }
        
        if (meta.kml_url) {
            filesHtml += `
                <div class="moonshot-item">
                    <div style="padding: 30px 20px; text-align: center; background: var(--base-3, #f5f5f5); min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--contrast-2, #666); margin-bottom: 12px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                        <div style="font-size: 18px; font-weight: bold; color: var(--contrast-2, #666);">KML File</div>
                        <div style="font-size: 13px; color: var(--contrast-3, #999); margin-top: 4px;">Property boundary for Google Earth</div>
                    </div>
                    <div class="moonshot-actions">
                        <a href="${meta.kml_url}" class="moonshot-btn" target="_blank" download>Download KML</a>
                    </div>
                </div>
            `;
        }
        
        container.innerHTML = `
            <style>
            .moonshot-gallery {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }
            .moonshot-item {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }
            .moonshot-item img {
                width: 100%;
                height: auto;
                display: block;
                border-bottom: 1px solid #eee;
                max-height: 300px;
                object-fit: cover;
            }
            .moonshot-actions {
                padding: 15px;
                display: flex;
                gap: 10px;
                margin-top: auto;
            }
            .moonshot-btn {
                flex: 1;
                background: var(--button, #1e73be);
                color: var(--contrast, #ffffff);
                padding: 10px;
                text-align: center;
                border-radius: 4px;
                text-decoration: none;
                font-size: 14px;
                font-weight: bold;
                cursor: pointer;
                border: none;
                display: inline-block;
                transition: opacity 0.2s;
            }
            .moonshot-btn:hover {
                opacity: 0.85;
            }
            .moonshot-header {
                text-align: center;
                margin-bottom: 30px;
            }
            </style>
            
            <div class="moonshot-header">
                <h2>Your Assets are Ready!</h2>
                <p>Preview and download your files below.</p>
            </div>
            
            <div class="moonshot-gallery">
                ${filesHtml}
            </div>
        `;
    }
});
</script>
