<?php
/**
 * Template Name: Fulfillment Page
 */

// Security Gate
$url_cust_id = isset($_GET['cust']) ? intval($_GET['cust']) : 0;
$order_id = isset($_GET['sc_order']) ? sanitize_text_field($_GET['order']) : '';
$current_user_id = get_current_user_id();

if ($url_cust_id === 0 || $url_cust_id !== $current_user_id) {
    wp_die('Unauthorized access. Please return to your dashboard and click the download link directly.');
}

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
            <!-- Cache Debug Timestamp: <?php echo date('Y-m-d H:i:s'); ?> -->
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
    const custId = "<?php echo esc_js($current_user_id); ?>";
    const orderId = "<?php echo esc_js($order_id); ?>";
    
    // Using your exact custom R2 domain
    const baseUrl = "https://pics.brokertricks.com"; 
    
    // We check if the main image loads instead of using fetch() to bypass any CORS restrictions on the R2 bucket
    const testImageUrl = `${baseUrl}/cust_${custId}/order_${orderId}/front_elevation.png`;
    const container = document.querySelector('.moonshot-gallery-container');
    
    if (!container) return;

    const img = new Image();
    
    img.onload = () => {
        // The image loaded successfully, render the custom assets
        
        const images = [
            { name: "Front Elevation", file: "front_elevation.png" },
            { name: "Top Down", file: "top_down.png" }
        ];

        let galleryHtml = images.map(img => `
            <div class="moonshot-item">
                <img src="${baseUrl}/cust_${custId}/order_${orderId}/${img.file}" alt="${img.name}" />
                <div style="padding: 10px 15px 0; font-weight: bold;">${img.name}</div>
                <div class="moonshot-actions">
                    <button class="moonshot-btn disabled" disabled title="Coming Soon">MLS/Web Size</button>
                    <button class="moonshot-btn disabled" disabled title="Coming Soon">Print Size</button>
                </div>
            </div>
        `).join('');

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
            }
            .moonshot-actions {
                padding: 15px;
                display: flex;
                gap: 10px;
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
            }
            .moonshot-btn:disabled, .moonshot-btn.disabled {
                background: #ccc;
                color: #666;
                cursor: not-allowed;
            }
            .moonshot-header {
                text-align: center;
                margin-bottom: 30px;
            }
            .moonshot-download-all {
                display: flex;
                gap: 20px;
                justify-content: center;
                margin-top: 20px;
                padding-top: 30px;
                border-top: 1px solid #eee;
            }
            </style>
            
            <div class="moonshot-header">
                <h2>Your Assets are Ready!</h2>
                <p>Preview and download your images below.</p>
            </div>
            
            <div class="moonshot-gallery">
                ${galleryHtml}
            </div>
            
            <div class="moonshot-download-all">
                <button class="moonshot-btn disabled" disabled style="max-width: 250px;" title="Coming Soon">Download All (MLS/Web)</button>
                <button class="moonshot-btn disabled" disabled style="max-width: 250px;" title="Coming Soon">Download All (Print Size)</button>
            </div>
        `;
    };
    
    img.onerror = () => {
        // The image failed to load (probably 404 because n8n is still working)
        container.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; background: var(--base-3, #f5f5f5); border-radius: 12px;">
                <h3 style="margin-bottom: 15px;">Generating your graphics...</h3>
                <p>We are rendering your custom assets right now. This page will automatically refresh when they are ready.</p>
            </div>
        `;
        // Auto-refresh the page every 15 seconds to check again
        setTimeout(() => location.reload(), 15000);
    };
    
    // Trigger the load
    img.src = testImageUrl;
});
</script>
